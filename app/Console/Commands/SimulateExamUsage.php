<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\ExamForm;
use App\Models\ExamQuestion;
use App\Models\ExamSubmission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SimulateExamUsage extends Command
{
    protected $signature = 'exam:simulate {--cleanup : Cleanup after simulation}';
    protected $description = 'Simulate a highly vibrant and varied exam scenario with different question counts, targeted users, and rich data';

    public function handle()
    {
        if ($this->option('cleanup')) {
            $this->cleanup();
            return;
        }

        $this->info('Starting Vibrant Real-World Simulation...');

        // 1. Admin
        User::firstOrCreate(
            ['username' => 'simulation_admin'],
            [
                'name' => 'Simulation Admin',
                'email' => 'admin_sim@example.com',
                'password' => Hash::make('password'),
                'role' => 1,
            ]
        );

        $schools = ['Global Hub Academy', 'Cyber Security Institute', 'SMK IT Nusantara', 'Binus University', 'Digital Talent High', 'Oxford Online', 'MIT OpenCourse', 'Nanyang Tech', 'Indonesian Cyber Center'];
        $examTitles = [
            'Web Security Fundamentals', 'Advanced Laravel Patterns', 'Digital Ethics & Law', 'UI/UX Design Sprint', 
            'Database Optimization Techniques', 'Cloud Infrastructure 101', 'Artificial Intelligence Ethics', 
            'Mobile App Development Quiz', 'Open Source Contribution Guide', 'Cyber Threat Intelligence',
            'Fullstack JavaScript Bootcamp', 'Data Science & Analytics', 'Network Administration Basics',
            'Agile Project Management', 'Blockchain Technology Intro', 'IoT Security Standards',
            'Frontend Performance Optimization', 'Backend Architecture Design', 'DevOps Jenkins Workflow',
            'React.js Component Lifecycle'
        ];

        // 2. Create 75 Students with organic names
        $students = [];
        for ($i = 1; $i <= 75; $i++) {
            $fullName = fake()->name();
            $students[] = User::create([
                'username' => Str::slug($fullName) . "_$i",
                'name' => $fullName,
                'email' => "student_$i@example.com",
                'password' => Hash::make('password'),
                'school' => $schools[array_rand($schools)],
                'role' => 0,
            ]);
        }
        $this->info('75 Students with organic names created.');

        // 3. Create 20 Exams with Varying Question Counts
        $exams = [];
        foreach ($examTitles as $index => $title) {
            $exams[] = ExamForm::create([
                'title' => $title,
                'description' => "Comprehensive assessment covering concepts from the $title module.",
                'status' => ($index < 16) ? 'active' : 'inactive',
                'duration' => rand(15, 120),
            ]);
            
            // RANDOM QUESTION COUNT: Different for every exam!
            $questionCount = rand(8, 25);
            for ($i = 1; $i <= $questionCount; $i++) {
                $type = ['option', 'connect', 'essay'][rand(0, 2)];
                $answerDetails = [];
                
                if ($type === 'option') {
                    $answerDetails = ['options' => [
                        ['id' => (string)Str::uuid(), 'text' => "Correct answer for topic $i", 'is_correct' => true],
                        ['id' => (string)Str::uuid(), 'text' => "Incorrect distractor for topic $i", 'is_correct' => false],
                    ]];
                } elseif ($type === 'connect') {
                    $answerDetails = ['pairs' => [
                        ['id' => (string)Str::uuid(), 'left' => "Condition $i", 'right' => "Result $i"],
                        ['id' => (string)Str::uuid(), 'left' => "Input $i", 'right' => "Output $i"],
                    ]];
                } elseif ($type === 'essay') {
                    $answerDetails = ['allow_attachments' => rand(0, 1)];
                }

                ExamQuestion::create([
                    'exam_form_id' => $exams[$index]->id,
                    'question_text' => "Professional question $i regarding $title features.",
                    'type' => $type,
                    'answer_details' => $answerDetails,
                    'context_image_path' => (rand(1, 100) <= 40) ? "https://picsum.photos/seed/" . Str::random(10) . "/1024/768" : null,
                ]);
            }
            $this->info("Exam '$title' created with $questionCount questions.");
        }

        // 4. Targeted Assignments (VARIED GROUPS)
        // Group A: student 1-10
        $groupAExam = ExamForm::create(['title' => 'Executive Leadership Quiz', 'status' => 'active', 'duration' => 30]);
        for ($i = 0; $i < 10; $i++) $groupAExam->assignedUsers()->attach($students[$i]->id);
        
        // Group B: student 20-40
        $groupBExam = ExamForm::create(['title' => 'Technical Intern Screening', 'status' => 'active', 'duration' => 60]);
        for ($i = 19; $i < 40; $i++) $groupBExam->assignedUsers()->attach($students[$i]->id);
        
        $this->info('Multiple Targeted Private Exams created with specific cohorts.');

        // 5. Simulate Vibrant Submissions (Eloquent)
        foreach ($students as $student) {
            // Randomly take 0 to 4 public exams
            $examCount = rand(0, 4);
            if ($examCount > 0) {
                $randomExams = array_rand($exams, $examCount);
                if (!is_array($randomExams)) $randomExams = [$randomExams];
                foreach ($randomExams as $exIdx) {
                    $this->createSubmission($student, $exams[$exIdx]);
                }
            }

            // Check if student is in group assignments
            $assignedExams = $student->assignedExams;
            foreach ($assignedExams as $assigned) {
                if (rand(1, 100) <= 85) { // 85% participation for targeted ones
                    $this->createSubmission($student, $assigned);
                }
            }
        }
        $this->info('Varied submissions generated (Some high scores, some in-progress, some pending).');

        // 6. Admin Grading Simulation (COMPREHENSIVE)
        // Fully grade the first 5 exams to ensure leaderboards are released
        foreach ($exams as $index => $exam) {
            if ($index < 5) {
                $exam->submissions()->where('status', 'pending')->get()->each(function($sub) {
                    $sub->update([
                        'score' => $sub->score + rand(1, 10),
                        'status' => 'completed'
                    ]);
                });
                $this->info("Fully graded all essays for '{$exam->title}' to release leaderboard.");
            }
        }
        
        // Randomly grade others
        ExamSubmission::where('status', 'pending')->inRandomOrder()->limit(20)->get()->each(function($sub) {
            $sub->update(['status' => 'completed', 'score' => $sub->score + rand(1, 5)]);
        });

        $this->info('--- HIGH-FIDELITY SIMULATION COMPLETE ---');
    }

    private function createSubmission($student, $exam)
    {
        $statusRand = rand(1, 100);
        $status = 'completed';
        if ($statusRand <= 15) $status = 'in_progress';
        elseif ($statusRand <= 40 && $exam->questions()->where('type', 'essay')->exists()) $status = 'pending';

        $totalQ = $exam->questions->count();
        $score = ($status === 'in_progress') ? 0 : rand(floor($totalQ*0.4), $totalQ);

        ExamSubmission::create([
            'user_id' => $student->id,
            'exam_form_id' => $exam->id,
            'score' => $score,
            'total_questions' => $totalQ,
            'answers_snapshot' => [],
            'status' => $status,
            'started_at' => now()->subHours(rand(1, 72)),
            'created_at' => ($status === 'in_progress') ? now() : now()->subMinutes(rand(10, 300)),
        ]);
    }

    protected function cleanup()
    {
        $this->info('Wiping all simulation data...');
        User::where('username', 'like', 'student_%')->get()->each(function($u) { $u->delete(); });
        if (User::where('role', 1)->where('username', '!=', 'simulation_admin')->exists()) {
            User::where('username', 'simulation_admin')->delete();
        }
        ExamForm::all()->each(function($e) {
            // Delete only our simulated ones based on titles or just wipe all if this is a test env
            $e->delete();
        });
        $this->info('Database cleaned.');
    }
}
