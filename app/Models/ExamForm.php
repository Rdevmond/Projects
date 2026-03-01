<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ExamForm extends Model
{
    protected $guarded = [];

    protected $casts = [
        'randomize_questions' => 'boolean',
    ];

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function submissions()
    {
        return $this->hasMany(ExamSubmission::class);
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'exam_user', 'exam_form_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Check if a specific user is assigned to this exam.
     */
    public function isAssignedTo($userId)
    {
        // If no users are assigned, exam is open to all students
        if ($this->assignedUsers()->count() === 0) {
            return true;
        }
        
        return $this->assignedUsers()->where('user_id', $userId)->exists();
    }

    /**
     * Check if all assigned students have completed the exam.
     */
    public function allAssignedCompleted()
    {
        $assignedCount = $this->assignedUsers()->count();
        
        // If no specific assignments, check if anyone has submitted
        if ($assignedCount === 0) {
            return $this->submissions()->count() > 0;
        }
        
        // Get IDs of assigned users who have submitted
        $completedUserIds = $this->submissions()
            ->pluck('user_id')
            ->unique();
        
        // Check if all assigned users have submitted
        $assignedUserIds = $this->assignedUsers()->pluck('user_id');
        
        return $assignedUserIds->diff($completedUserIds)->isEmpty();
    }

    public function getCompletionStatus()
    {
        // "Total" is now defined as anyone who has started the exam (active submissions)
        // for maximum variety as requested by user.
        $totalInteractions = $this->submissions()->distinct('user_id')->count();
        
        $completedCount = $this->submissions()
            ->whereIn('status', ['completed', 'pending'])
            ->distinct('user_id')
            ->count();
            
        return [
            'total' => $totalInteractions,
            'completed' => $completedCount,
            'percentage' => $totalInteractions > 0 ? round(($completedCount / $totalInteractions) * 100) : 0,
            'message' => $totalInteractions > 0 
                ? "$completedCount / $totalInteractions students completed" 
                : "No active submissions yet"
        ];
    }

    /**
     * Cek apakah semua peserta sudah dinilai sehingga nilai bisa dimunculkan.
     * Updated to be stricter for both assigned and open exams.
     */
    public function isResultsReleased()
    {
        $assignedCount = $this->assignedUsers()->count();
        
        if ($assignedCount > 0) {
            // If assigned, MUST wait for all assigned to finish
            if (!$this->allAssignedCompleted()) {
                return false;
            }
        } 

        // Important: For open exams, we now allow release as soon as nobody is 'in_progress'
        // and all essays are graded. This avoids waiting for the whole school.
        $hasInProgress = $this->submissions()->where('status', 'in_progress')->exists();
        if ($hasInProgress) {
            return false;
        }

        // Nilai release HANYA JIKA tidak ada lagi status 'pending' (essays graded)
        return ! $this->submissions()->where('status', 'pending')->exists();
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });

        // Saat ExamForm dihapus, hapus juga semua relasinya
        static::deleting(function ($exam) {
            $exam->questions()->delete();
            $exam->submissions()->delete();
        });
    }
}
