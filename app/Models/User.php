<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isMurid(): bool
    {
        return $this->role === 'murid';
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'created_by');
    }

    public function examSessions()
    {
        return $this->hasMany(ExamSession::class);
    }
}
