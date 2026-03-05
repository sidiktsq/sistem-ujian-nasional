<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSession;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $murid = Auth::user();

        $availableExams = Exam::where('is_active', true)
            ->withCount('questions')
            ->get();

        $mySessions = ExamSession::where('user_id', $murid->id)
            ->with(['exam', 'answers.question'])
            ->where('status', 'submitted')
            ->latest('submitted_at')
            ->take(5)
            ->get();

        $totalUjian = ExamSession::where('user_id', $murid->id)->where('status', 'submitted')->count();
        
        // Only calculate scores for sessions that are fully graded
        $gradedSessions = ExamSession::where('user_id', $murid->id)
            ->where('status', 'submitted')
            ->whereDoesntHave('answers', function($query) {
                $query->where('grading_status', 'pending');
            })
            ->get();
            
        $avgScore = $gradedSessions->avg('score') ?? 0;
        $bestScore = $gradedSessions->max('score') ?? 0;

        return view('murid.dashboard', compact(
            'availableExams', 'mySessions', 'totalUjian', 'avgScore', 'bestScore'
        ));
    }
}
