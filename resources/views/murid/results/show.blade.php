@extends('layouts.app')
@section('page-title', 'Hasil Ujian: ' . $session->exam->title)

@section('content')
<div style="max-width:800px; margin:0 auto">
    <a href="{{ route('murid.results.index') }}" class="btn btn-secondary btn-sm" style="margin-bottom:20px">
        <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
    </a>

    {{-- Score Header --}}
    @php
        $hasMultipleChoice = $session->answers->contains(function($answer) {
            return $answer->question->question_type === 'multiple_choice';
        });
        $hasEssay = $session->answers->contains(function($answer) {
            return $answer->question->question_type === 'essay';
        });
        $hasMixedTypes = $hasMultipleChoice && $hasEssay;
    @endphp
    
    <div class="card" style="margin-bottom:24px; text-align:center; background:linear-gradient(135deg, var(--dark2), rgba(79,70,229,0.05))">
        @if($hasEssay)
            <p style="font-size:13px; color:var(--text-muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:12px">Status Ujian</p>
            <div style="font-size:24px; font-weight:700; color:#F59E0B; line-height:1; margin:20px 0">
                Soal sudah di kirim
            </div>
        @else
            <p style="font-size:13px; color:var(--text-muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:12px">Skor Akhir Anda</p>
            <div style="font-size:72px; font-weight:900; color:{{ $session->score >= 70 ? '#34D399' : '#F87171' }}; line-height:1">
                {{ number_format($session->score, 0) }}
            </div>
            <div style="margin-top:16px"><span class="badge {{ $session->score >= 70 ? 'badge-green' : 'badge-red' }}" style="padding:8px 24px; font-size:16px; font-weight:800">GRADE {{ $session->grade }}</span></div>
        @endif
        
        @if(!$hasEssay)
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:12px; margin-top:40px; padding-top:24px; border-top:1px solid var(--border)">
            <div>
                <div style="font-size:11px; color:var(--text-muted); margin-bottom:4px">BENAR</div>
                <div style="font-size:18px; font-weight:700; color:#34D399">{{ $session->answers->where('is_correct', true)->count() }}</div>
            </div>
            <div>
                <div style="font-size:11px; color:var(--text-muted); margin-bottom:4px">SALAH</div>
                <div style="font-size:18px; font-weight:700; color:#F87171">{{ $session->answers->where('is_correct', false)->where('grading_status', 'graded')->count() }}</div>
            </div>
            <div>
                <div style="font-size:11px; color:var(--text-muted); margin-bottom:4px">MENUNGGU PENILAIAN</div>
                <div style="font-size:18px; font-weight:700; color:#F59E0B">{{ $session->answers->where('grading_status', 'pending')->count() }}</div>
            </div>
            <div>
                <div style="font-size:11px; color:var(--text-muted); margin-bottom:4px">TOTAL POIN</div>
                <div style="font-size:18px; font-weight:700">{{ $session->earned_points }} / {{ $session->total_points }}</div>
            </div>
        </div>
        @endif
    </div>

    {{-- Answers Review --}}
    <h3 style="font-size:16px; font-weight:700; margin-bottom:16px">Tinjauan Jawaban</h3>
    @foreach($session->answers as $index => $answer)
    <div class="card" style="margin-bottom:16px; border-left:4px solid {{ $answer->question->question_type === 'essay' ? (($answer->grading_status === 'pending') ? '#F59E0B' : ($answer->is_correct ? '#34D399' : '#F87171')) : ($answer->is_correct ? '#34D399' : '#F87171') }}">
        <div style="display:flex; justify-content:space-between; margin-bottom:12px">
            <span style="font-size:12px; font-weight:700; color:var(--text-muted)">SOAL #{{ $index + 1 }}</span>
            @if($answer->question->question_type === 'essay')
                <span class="badge {{ $answer->grading_status === 'pending' ? 'badge-yellow' : ($answer->is_correct ? 'badge-green' : 'badge-red') }}" style="background:{{ $answer->grading_status === 'pending' ? 'rgba(245,158,11,0.2); border:1px solid rgba(245,158,11,0.5); color:#FCD34D' : '' }}">
                    @if($answer->grading_status === 'pending')
                        JAWABAN SUDAH DIKIRIM
                    @else
                        {{ $answer->is_correct ? 'BENAR' : 'SALAH' }}
                    @endif
                </span>
            @else
                <span class="badge {{ $answer->is_correct ? 'badge-green' : 'badge-red' }}">
                    {{ $answer->is_correct ? 'BENAR' : 'SALAH' }}
                </span>
            @endif
        </div>
        
        <p style="font-weight:600; font-size:15px; margin-bottom:16px">{{ $answer->question->question_text }}</p>

        @if($answer->question->question_type === 'multiple_choice')
            <div style="display:grid; grid-template-columns:1fr; gap:8px">
                @foreach($answer->question->options as $option)
                    <div style="padding:10px 14px; border-radius:8px; font-size:13px; display:flex; align-items:center; gap:10px; 
                        @if($option->id == $answer->option_id)
                            @if($answer->is_correct)
                                background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); color:#34D399;
                            @else
                                background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2); color:#F87171;
                            @endif
                        @elseif($option->is_correct)
                            background:rgba(16,185,129,0.05); border:1px solid rgba(16,185,129,0.1); color:#34D399; border-style:dashed;
                        @else
                            background:rgba(255,255,255,0.02); border:1px solid var(--border); color:var(--text-muted);
                        @endif
                    ">
                        @if($option->id == $answer->option_id)
                            <i class="fas {{ $answer->is_correct ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        @else
                            <i class="far fa-circle"></i>
                        @endif
                        {{ $option->option_text }}
                    </div>
                @endforeach
            </div>
        @else
            {{-- Essay Answer Display --}}
            <div style="padding:16px; background:rgba(255,255,255,0.02); border:1px solid var(--border); border-radius:8px; font-size:14px; line-height:1.6">
                {!! nl2br(e($answer->essay_answer)) !!}
            </div>
        @endif
    </div>
    @endforeach
</div>
@endsection
