<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSubmission extends Model
{
    protected $guarded = [];

    // Important: Convert the summary JSON back to an array for the result view
    protected $casts = [
        'answers_snapshot' => 'array',
        'started_at' => 'datetime',
        'created_at' => 'datetime',
    ];

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
    }

    // Get the student who owns this submission
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get the exam that was taken
    public function examForm()
    {
        return $this->belongsTo(ExamForm::class);
    }

    public function getDurationAttribute()
    {
        if (! $this->started_at || ! $this->created_at) {
            return '-';
        }

        return $this->started_at->diffInMinutes($this->created_at) . ' min';
    }
}
