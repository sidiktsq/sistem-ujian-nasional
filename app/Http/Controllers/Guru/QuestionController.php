<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function index(Exam $exam)
    {
        $this->authorizeExam($exam);
        $questions = $exam->questions()->with('options')->get();
        return view('guru.questions.index', compact('exam', 'questions'));
    }

    public function create(Exam $exam)
    {
        $this->authorizeExam($exam);
        return view('guru.questions.create', compact('exam'));
    }

    public function store(Request $request, Exam $exam)
    {
        $this->authorizeExam($exam);

        $data = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,essay',
            'points' => 'required|integer|min:1',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*' => 'required_if:question_type,multiple_choice|string',
            'correct_option' => 'required_if:question_type,multiple_choice|integer',
        ]);

        DB::transaction(function () use ($exam, $data, $request) {
            $order = $exam->questions()->max('order') + 1;

            $question = Question::create([
                'exam_id' => $exam->id,
                'question_text' => $data['question_text'],
                'question_type' => $data['question_type'],
                'points' => $data['points'],
                'order' => $order,
            ]);

            if ($data['question_type'] === 'multiple_choice') {
                foreach ($data['options'] as $index => $optionText) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $optionText,
                        'is_correct' => ($index == $request->correct_option),
                        'order' => $index + 1,
                    ]);
                }
            }
        });

        return redirect()->route('guru.exams.questions.index', $exam)
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(Exam $exam, Question $question)
    {
        $this->authorizeExam($exam);
        $question->load('options');
        return view('guru.questions.edit', compact('exam', 'question'));
    }

    public function update(Request $request, Exam $exam, Question $question)
    {
        $this->authorizeExam($exam);

        $data = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,essay',
            'points' => 'required|integer|min:1',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*' => 'required_if:question_type,multiple_choice|string',
            'correct_option' => 'required_if:question_type,multiple_choice|integer',
        ]);

        DB::transaction(function () use ($question, $exam, $data, $request) {
            $question->update([
                'question_text' => $data['question_text'],
                'question_type' => $data['question_type'],
                'points' => $data['points'],
            ]);

            if ($data['question_type'] === 'multiple_choice') {
                $question->options()->delete();
                foreach ($data['options'] as $index => $optionText) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $optionText,
                        'is_correct' => ($index == $request->correct_option),
                        'order' => $index + 1,
                    ]);
                }
            }
        });

        return redirect()->route('guru.exams.questions.index', $exam)
            ->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Exam $exam, Question $question)
    {
        $this->authorizeExam($exam);
        $question->delete();
        return redirect()->route('guru.exams.questions.index', $exam)
            ->with('success', 'Soal berhasil dihapus.');
    }

    private function authorizeExam(Exam $exam)
    {
        if ($exam->created_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
}
