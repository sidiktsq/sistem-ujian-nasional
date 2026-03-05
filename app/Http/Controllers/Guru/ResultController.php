<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    public function index(Exam $exam)
    {
        if ($exam->created_by !== Auth::id()) {
            abort(403);
        }

        $sessions = $exam->sessions()
            ->with('user')
            ->where('status', 'submitted')
            ->orderByDesc('score')
            ->paginate(15);

        return view('guru.results.index', compact('exam', 'sessions'));
    }

    public function show(Exam $exam, ExamSession $session)
    {
        if ($exam->created_by !== Auth::id()) {
            abort(403);
        }

        $session->load(['user', 'answers.question.options', 'answers.option', 'exam.questions']);

        return view('guru.results.show', compact('exam', 'session'));
    }

    public function gradeEssay(Request $request, Exam $exam, ExamSession $session, StudentAnswer $answer)
    {
        if ($exam->created_by !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'is_correct' => 'required|boolean',
            'points_earned' => 'required|integer|min:0|max:' . $answer->question->points,
        ]);

        DB::transaction(function () use ($answer, $session, $data) {
            $answer->update([
                'is_correct' => $data['is_correct'],
                'points_earned' => $data['points_earned'],
                'grading_status' => 'graded',
                'graded_by' => Auth::id(),
                'graded_at' => now(),
            ]);

            // Update exam session score
            $totalPoints = $session->exam->getTotalPointsAttribute();
            $earnedPoints = $session->answers->sum('points_earned');
            $score = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100, 2) : 0;

            $session->update([
                'score' => $score,
                'earned_points' => $earnedPoints,
            ]);
        });

        return back()->with('success', 'Jawaban essay telah dinilai.');
    }
}

