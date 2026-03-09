<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Exam;
use App\Models\ExamSession;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_gurus' => User::where('role', 'guru')->count(),
            'total_murids' => User::where('role', 'murid')->count(),
            'total_exams' => Exam::count(),
            'total_sessions' => ExamSession::count(),
            'completed_sessions' => ExamSession::where('status', 'submitted')->count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentExams = Exam::with('creator')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentExams'));
    }
}
