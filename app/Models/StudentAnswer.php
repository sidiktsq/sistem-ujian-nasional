<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_session_id',
        'question_id',
        'option_id',
        'essay_answer',
        'is_correct',
        'points_earned',
        'grading_status',
        'graded_by',
        'graded_at',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'graded_at' => 'datetime',
    ];

    public function examSession()
    {
        return $this->belongsTo(ExamSession::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
