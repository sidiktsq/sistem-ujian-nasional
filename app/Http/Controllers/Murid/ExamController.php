<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function index()
    {
        $murid = Auth::user();
        $exams = Exam::where('is_active', true)
            ->withCount('questions')
            ->with(['sessions' => function ($q) use ($murid) {
                $q->where('user_id', $murid->id);
            }])
            ->get();

        return view('murid.exams.index', compact('exams'));
    }

    public function show(Exam $exam)
    {
        abort_unless($exam->is_active, 404);
        $exam->load('questions');

        $existingSession = ExamSession::where('exam_id', $exam->id)
            ->where('user_id', Auth::id())
            ->where('status', 'submitted')
            ->first();

        return view('murid.exams.show', compact('exam', 'existingSession'));
    }

    public function start(Exam $exam)
    {
        abort_unless($exam->is_active, 403);

        
        $submitted = ExamSession::where('exam_id', $exam->id)
            ->where('user_id', Auth::id())
            ->where('status', 'submitted')
            ->exists();

        if ($submitted) {
            return redirect()->route('murid.exams.show', $exam)
                ->with('error', 'Anda sudah mengerjakan ujian ini.');
        }

       
        $session = ExamSession::where('exam_id', $exam->id)
            ->where('user_id', Auth::id())
            ->where('status', 'in_progress')
            ->first();

        if (!$session) {
            $session = ExamSession::create([
                'exam_id' => $exam->id,
                'user_id' => Auth::id(),
                'started_at' => now(),
                'status' => 'in_progress',
            ]);
        }

        $questions = $exam->questions()->with('options')->get();

        return view('murid.exams.take', compact('exam', 'session', 'questions'));
    }

    public function submit(Request $request, Exam $exam, ExamSession $session)
    {
        if ($session->user_id !== Auth::id() || $session->exam_id !== $exam->id) {
            abort(403);
        }
        if ($session->status === 'submitted') {
            return redirect()->route('murid.results.show', $session)
                ->with('info', 'Ujian sudah disubmit sebelumnya.');
        }

        $answers = $request->input('answers', []);

        DB::transaction(function () use ($exam, $session, $answers) {
            $earnedPoints = 0;
            $totalPoints = 0;

            $questions = $exam->questions()->with('options')->get();

            foreach ($questions as $question) {
                $totalPoints += $question->points;
                $answerValue = $answers[$question->id] ?? null;

                $isCorrect = false;
                $pointsEarned = 0;
                $optionId = null;
                $essayAnswer = null;
                $gradingStatus = 'pending'; // Default untuk semua

                if ($question->question_type === 'multiple_choice') {
                    $gradingStatus = 'graded'; // Multiple choice langsung di-grade
                    if ($answerValue) {
                        $optionId = $answerValue;
                        $correctOption = $question->options->where('is_correct', true)->first();
                        if ($correctOption && $correctOption->id == $optionId) {
                            $isCorrect = true;
                            $pointsEarned = $question->points;
                            $earnedPoints += $pointsEarned;
                        }
                    }
                } else {
                    
                    $essayAnswer = $answerValue;
                    $gradingStatus = 'pending';
                }

                StudentAnswer::updateOrCreate(
                    [
                        'exam_session_id' => $session->id,
                        'question_id' => $question->id,
                    ],
                    [
                        'option_id' => $optionId,
                        'essay_answer' => $essayAnswer,
                        'is_correct' => $isCorrect,
                        'points_earned' => $pointsEarned,
                        'grading_status' => $gradingStatus,
                    ]
                );
            }

            $score = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100, 2) : 0;

            $session->update([
                'submitted_at' => now(),
                'status' => 'submitted',
                'score' => $score,
                'total_points' => $totalPoints,
                'earned_points' => $earnedPoints,
            ]);
        });

        return redirect()->route('murid.results.show', $session)
            ->with('success', 'Ujian berhasil dikumpulkan! Lihat nilai Anda di bawah.');
    }
}
