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

    public function confirmResults(Exam $exam, ExamSession $session)
    {
        if ($exam->created_by !== Auth::id()) {
            abort(403);
        }

        // Check if there are still pending essay answers
        $hasPendingEssays = $session->answers()
            ->whereHas('question', function($q) {
                $q->where('question_type', 'essay');
            })
            ->where('grading_status', 'pending')
            ->exists();

        if ($hasPendingEssays) {
            return back()->with('error', 'Masih ada jawaban essay yang belum dinilai.');
        }

        DB::transaction(function () use ($session) {
            // Mark all remaining pending answers (usually multiple choice) as graded
            $session->answers()->where('grading_status', 'pending')->update([
                'grading_status' => 'graded',
                'graded_at' => now(),
            ]);

            // Final recalc of session score just in case
            $totalPoints = $session->exam->questions->sum('points');
            $earnedPoints = $session->answers->sum('points_earned');
            $score = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100, 2) : 0;

            $session->update([
                'score' => $score,
                'earned_points' => $earnedPoints,
            ]);
        });

        return back()->with('success', 'Nilai ujian telah dikonfirmasi dan dirilis ke murid.');
    }
}

