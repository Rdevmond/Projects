<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $guarded = [];

    // Automatically convert the JSON from DB to a PHP Array
    protected $casts = [
        'answer_details'   => 'array',
        'answers_snapshot' => 'array',
        'is_required'      => 'boolean',
        'duration'         => 'integer',
    ];

    public function exam()
    {
        return $this->belongsTo(ExamForm::class);
    }
}
