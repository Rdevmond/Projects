<?php
namespace App\Http\Controllers;

use App\Models\ExamForm;
use App\Models\ExamSubmission;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ExamController extends Controller
{
    /**
     * ADMIN: Dashboard Home
     */
    public function index(Request $request)
    {
        $totalExamsCount = ExamForm::count();
        $liveExamsCount = ExamForm::where('status', 'active')->count();
        $totalSubmissions = ExamSubmission::whereHas('examForm', function($query) {
            $query->where('status', 'active');
        })->count();

        $query = ExamForm::latest();

        // Search Filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->has('status') && $request->status != 'all' && $request->status != '') {
            $query->where('status', $request->status);
        }

        $exams = $query->paginate(10)->withQueryString();

        return view('admin.dashboard', compact(
            'exams',
            'totalExamsCount',
            'liveExamsCount',
            'totalSubmissions'
        ));
    }

    /**
     * ADMIN: Form Buat Soal Baru
     */
    public function create()
    {
        return view('admin.soal');
    }

    /**
     * ADMIN: Simpan Soal Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'exam_title' => 'required|string|max:255',
            'questions' => 'required|array|min:1',
        ]);

        $exam = ExamForm::create([
            'title' => $request->input('exam_title'),
            'description' => $request->input('exam_description'),
            'duration' => $request->input('duration'),
            'status' => 'active',
        ]);

        $this->saveQuestions($exam, $request);
        
        // Save assigned users
        if ($request->has('assigned_users')) {
            $exam->assignedUsers()->sync($request->input('assigned_users'));
        }

        return redirect()->route('admin.dashboard')->with('success', 'Exam published successfully!');
    }

    /**
     * ADMIN: Edit Exam
     */
    public function edit(ExamForm $exam)
    {
        $exam->load('questions');
        return view('admin.edit_soal', compact('exam'));
    }

    /**
     * ADMIN: Update Exam
     */
    public function update(Request $request, ExamForm $exam)
    {

        $exam->update([
            'title' => $request->input('exam_title'),
            'description' => $request->input('exam_description'),
            'duration' => $request->input('duration'),
        ]);

        // Hapus soal lama, lalu simpan ulang (untuk handle re-ordering/hapus-tambah soal di Alpine.js)
        $exam->questions()->delete();
        $this->saveQuestions($exam, $request);
        
        // Sync assigned users
        if ($request->has('assigned_users')) {
            $exam->assignedUsers()->sync($request->input('assigned_users'));
        } else {
            $exam->assignedUsers()->detach(); // Remove all if none selected
        }

        return redirect()->route('admin.dashboard')->with('success', 'Exam updated successfully!');
    }

    /**
     * ADMIN: Delete Exam
     */
    public function destroy(ExamForm $exam)
    {
        $exam->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Exam deleted successfully!');
    }

    /**
     * Helper logic: Memproses penyimpanan soal dan upload gambar (SANGAT KRUSIAL)
     */
    private function saveQuestions($exam, $request)
    {
        $questionsData = $request->input('questions', []);

        foreach ($questionsData as $index => $qData) {
            $contextImagePath = null;

            // Cek Context Image
            if ($request->hasFile("questions.{$index}.context_image")) {
                $cFile = $request->file("questions.{$index}.context_image");

                // Cek real path: Jika kosong, berarti PHP gagal buat file temp
                if ($cFile->isValid() && !empty($cFile->getRealPath())) {
                    $contextImagePath = $cFile->store('exam_context', 'public');
                } else {
                    Log::warning("File upload gagal di soal indeks $index. Error: " . $cFile->getErrorMessage());
                }
            }

            $type = $qData['type'] ?? 'option';
            $answerDetails = [];

            if ($type === 'option') {
                $items = [];
                foreach ($qData['options'] ?? [] as $optIndex => $opt) {
                    $path = null;

                    if ($request->hasFile("questions.{$index}.options.{$optIndex}.file")) {
                        $oFile = $request->file("questions.{$index}.options.{$optIndex}.file");

                        if ($oFile->isValid() && !empty($oFile->getRealPath())) {
                            $path = $oFile->store('exam_options', 'public');
                        }
                    }

                    $items[] = [
                        'id' => (string) Str::uuid(),
                        'text' => $opt['text'] ?? '',
                        'image' => $path,
                        'is_correct' => in_array((string) $optIndex, (array) ($qData['correct'] ?? [])),
                    ];
                }
                $answerDetails = ['options' => $items];
            }
            // ... (Bagian Connect & Essay tetap seperti sebelumnya)
            elseif ($type === 'connect') {
                $pairs = [];
                foreach ($qData['pairs'] ?? [] as $pair) {
                    $pairs[] = [
                        'id' => (string) Str::uuid(),
                        'left' => $pair['left'] ?? '',
                        'right' => $pair['right'] ?? '',
                    ];
                }
                $answerDetails = ['pairs' => $pairs];
            } elseif ($type === 'essay') {
                $answerDetails = ['allow_attachments' => isset($qData['essay_allow_attachment'])];
            }

            ExamQuestion::create([
                'exam_form_id' => $exam->id,
                'question_text' => $qData['text'] ?? 'Untitled Question',
                'type' => $type,
                'context_image_path' => $contextImagePath,
                'is_required' => isset($qData['required']),
                'answer_details' => $answerDetails,
            ]);
        }
    }

    public function toggleStatus(Request $request, ExamForm $exam)
    {
        $exam->status = ($exam->status === 'active') ? 'inactive' : 'active';
        $exam->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $exam->status,
                'message' => 'Status updated successfully'
            ]);
        }

        return back()->with('success', 'Status ujian berhasil diubah!');
    }

    public function showSpecs(ExamForm $exam)
    {

        // Ambil data submission, diurutkan dari skor tertinggi
        $submissions = ExamSubmission::with('user')
            ->where('exam_form_id', $exam->id)
            ->orderByDesc('score')
            ->get();

        return view('admin.specs', compact('exam', 'submissions'));
    }

    /**
     * STUDENT: Submit Logic
     */
    public function submit(Request $request, ExamForm $exam)
    {
        // Cari submission yang sedang berjalan (in_progress)
        $submission = ExamSubmission::where('user_id', Auth::id())
            ->where('exam_form_id', $exam->id)
            ->first();

        if ($submission && $submission->status !== 'in_progress') {
            return redirect()->route('student.result', $submission);
        }
        
        $exam->load('questions');
        $studentAnswers = $request->input('answers', []);
        $score = 0;
        $hasEssay = false;
        $summary = [];

        foreach ($exam->questions as $q) {
            $isCorrect = false;
            $details = $q->answer_details;

            if ($q->type === 'option') {
                $correctIds = collect($details['options'] ?? [])->where('is_correct', true)->pluck('id')->toArray();
                $submittedIds = (array) ($studentAnswers[$q->id] ?? []);

                if (!empty($correctIds) && count($correctIds) === count($submittedIds) && empty(array_diff($correctIds, $submittedIds))) {
                    $isCorrect = true;
                }
            } elseif ($q->type === 'connect') {
                $correctPairs = $details['pairs'] ?? [];
                $submittedMatches = $studentAnswers[$q->id] ?? [];
                $matchesAll = true;

                if (empty($submittedMatches)) {
                    $matchesAll = false;
                } else {
                    foreach ($correctPairs as $pair) {
                        if (($submittedMatches[$pair['left']] ?? null) !== $pair['right']) {
                            $matchesAll = false;
                        }
                    }
                }
                $isCorrect = $matchesAll;
            } elseif ($q->type === 'essay') {
                $hasEssay = true;
            }

            if ($isCorrect) {
                $score++;
            }

            $summary[] = [
                'question_id' => $q->id,
                'is_correct' => $isCorrect,
                'answer' => $studentAnswers[$q->id] ?? null,
            ];
        }

        if ($submission) {
            $submission->update([
                'score' => $score,
                'total_questions' => $exam->questions->count(),
                'answers_snapshot' => $summary,
                'status' => $hasEssay ? 'pending' : 'completed',
            ]);
        } else {
            // Fallback jika karena suatu hal record in_progress tidak ada
            $submission = ExamSubmission::create([
                'user_id' => Auth::id(),
                'exam_form_id' => $exam->id,
                'score' => $score,
                'total_questions' => $exam->questions->count(),
                'answers_snapshot' => $summary,
                'status' => $hasEssay ? 'pending' : 'completed',
                'started_at' => now(),
            ]);
        }

        return redirect()->route('student.result', $submission);
    }

    public function showSubmissions(ExamForm $exam)
    {
        $submissions = ExamSubmission::with('user')->where('exam_form_id', $exam->id)->latest()->get();
        return view('admin.submissions.index', compact('exam', 'submissions'));
    }

    public function leaderboard(ExamForm $exam)
    {
        $submissions = ExamSubmission::with('user')->where('exam_form_id', $exam->id)->where('status', 'completed')->orderBy('score', 'desc')->get();
        return view('admin.leaderboard', compact('exam', 'submissions'));
    }

    public function studentIndex()
    {
        $userId = Auth::id();
        
        // Get exams where user is assigned OR no assignments exist (open to all)
        $exams = ExamForm::where('status', 'active')
            ->where(function($query) use ($userId) {
                $query->whereHas('assignedUsers', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->orWhereDoesntHave('assignedUsers');
            })
            ->with('assignedUsers', 'submissions')
            ->latest()
            ->paginate(9);
        
        $history = ExamSubmission::with('examForm')
            ->where('user_id', $userId)
            ->latest()
            ->get();
            
        return view('student.list', compact('exams', 'history'));
    }

    /**
     * Show confirmation page before starting exam
     */
    public function showConfirmation(ExamForm $exam)
    {
        $exam->load('questions');
        
        // Check if user is assigned
        if (!$exam->isAssignedTo(Auth::id())) {
            return redirect()->route('student.exams')
                ->with('error', 'You are not assigned to this exam.');
        }
        
        // Check if already submitted
        $existing = ExamSubmission::where('user_id', Auth::id())
            ->where('exam_form_id', $exam->id)
            ->first();
        
        if ($existing) {
            return redirect()->route('student.result', $existing);
        }
        
        return view('student.exam_confirmation', compact('exam'));
    }

    /**
     * Start exam and show exam page (timer starts here)
     */
    public function startExam(Request $request, ExamForm $exam)
    {
        $exam->load('questions');
        
        // Verify assignment
        if (!$exam->isAssignedTo(Auth::id())) {
            abort(403, 'Not authorized');
        }
        
        // Check for existing submission
        $existing = ExamSubmission::where('user_id', Auth::id())
            ->where('exam_form_id', $exam->id)
            ->first();
        
        if ($existing && $existing->status !== 'in_progress') {
            return redirect()->route('student.result', $existing);
        }

        if (!$existing) {
            $existing = ExamSubmission::create([
                'user_id' => Auth::id(),
                'exam_form_id' => $exam->id,
                'score' => 0,
                'total_questions' => $exam->questions->count(),
                'answers_snapshot' => [],
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }
        
        // Timer will be initialized by JavaScript in the view
        return view('student.take_exam', compact('exam'));
    }

    public function showResult(ExamSubmission $submission)
    {
        $submission->load('examForm.submissions');
        
        // Authorization check: student can only view their own result
        if ($submission->user_id !== Auth::id()) {
            abort(403);
        }

        // Gunakan method isResultsReleased() yang sudah kita buat di Model ExamForm
        $isReleased = $submission->examForm->isResultsReleased();

        return view('student.result', compact('submission', 'isReleased'));
    }

    public function reviewSubmission(ExamSubmission $submission)
    {
        $submission->load('examForm.questions', 'user');
        
        // Index snapshot by question_id for easy access in view
        $studentAns = [];
        foreach ($submission->answers_snapshot as $item) {
            $studentAns[$item['question_id']] = $item['answer'];
        }
        
        return view('admin.review_essay', compact('submission', 'studentAns'));
    }

    public function gradeEssay(Request $request, ExamSubmission $submission)
    {
        $request->validate([
            'manual_points' => 'required|numeric|min:0|max:1',
        ]);

        // Admin mengisi skor total
        $submission->update([
            'score' => $submission->score + $request->manual_points,
            'status' => 'completed',
        ]);

        return redirect()->route('submissions.index', $submission->examForm)
            ->with('success', 'Nilai mahasiswa berhasil diperbarui!');
    }

    public function destroySubmission(ExamSubmission $submission)
    {
        $exam = $submission->examForm;
        $submission->delete();

        return redirect()->route('submissions.index', $exam)
            ->with('success', 'Exam submission has been permanently removed.');
    }

    public function downloadReport(ExamForm $exam)
    {
        $submissions = ExamSubmission::with('user')->where('exam_form_id', $exam->id)->orderByDesc('score')->get();

        $fileName = 'Report_' . str_replace(' ', '_', $exam->title) . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];

        $columns = ['Rank', 'Name', 'Email', 'Score', 'Total Questions', 'Status', 'Submitted At'];

        $callback = function () use ($submissions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($submissions as $index => $sub) {
                fputcsv($file, [
                    $index + 1,
                    $sub->user->name,
                    $sub->user->email,
                    $sub->score,
                    $sub->total_questions,
                    $sub->status,
                    $sub->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
