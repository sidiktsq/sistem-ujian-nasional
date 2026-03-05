<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSession;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = Auth::user();
        $totalUjian = Exam::where('created_by', $guru->id)->count();
        $ujianAktif = Exam::where('created_by', $guru->id)->where('is_active', true)->count();
        $totalSesi = ExamSession::whereHas('exam', function($q) use ($guru) {
            $q->where('created_by', $guru->id);
        })->count();
        $selesai = ExamSession::whereHas('exam', function($q) use ($guru) {
            $q->where('created_by', $guru->id);
        })->where('status', 'submitted')->count();

        $recentExams = Exam::where('created_by', $guru->id)
            ->withCount('questions')
            ->with(['sessions' => function($q) { $q->where('status', 'submitted'); }])
            ->latest()
            ->take(5)
            ->get();

        return view('guru.dashboard', compact(
            'totalUjian', 'ujianAktif', 'totalSesi', 'selesai', 'recentExams'
        ));
    }
}
