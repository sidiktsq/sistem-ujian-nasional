<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::where('created_by', Auth::id())
            ->withCount('questions')
            ->withCount(['sessions as submitted_count' => function($q) {
                $q->where('status', 'submitted');
            }])
            ->latest()
            ->paginate(10);

        return view('guru.exams.index', compact('exams'));
    }

    public function create()
    {
        return view('guru.exams.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1|max:300',
            'is_active' => 'boolean',
        ]);

        $data['created_by'] = Auth::id();
        $data['is_active'] = $request->boolean('is_active');

        $exam = Exam::create($data);

        return redirect()->route('guru.exams.questions.index', $exam)
            ->with('success', 'Ujian berhasil dibuat! Silakan tambahkan soal.');
    }

    public function edit(Exam $exam)
    {
        $this->authorizeExam($exam);
        return view('guru.exams.edit', compact('exam'));
    }

    public function update(Request $request, Exam $exam)
    {
        $this->authorizeExam($exam);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1|max:300',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $exam->update($data);

        return redirect()->route('guru.exams.index')
            ->with('success', 'Ujian berhasil diperbarui.');
    }

    public function destroy(Exam $exam)
    {
        $this->authorizeExam($exam);
        $exam->delete();
        return redirect()->route('guru.exams.index')
            ->with('success', 'Ujian berhasil dihapus.');
    }

    private function authorizeExam(Exam $exam)
    {
        if ($exam->created_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
}
