<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function index()
    {
        $sessions = ExamSession::where('user_id', Auth::id())
            ->with('exam')
            ->where('status', 'submitted')
            ->latest('submitted_at')
            ->paginate(10);

        return view('murid.results.index', compact('sessions'));
    }

    public function show(ExamSession $session)
    {
        if ($session->user_id !== Auth::id()) {
            abort(403);
        }

        $session->load(['exam.questions.options', 'answers.question.options', 'answers.option']);

        return view('murid.results.show', compact('session'));
    }
}
