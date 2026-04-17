@extends('layouts.app')
@section('page-title', 'Hasil Ujian: ' . $session->exam->title)

@section('content')
<div style="max-width:800px; margin:0 auto">
    <a href="{{ route('murid.results.index') }}" class="btn btn-secondary btn-sm" style="margin-bottom:20px">
        <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
    </a>

    {{-- Score Header --}}
    @php
        $isGraded = !$session->answers->contains('grading_status', 'pending');
    @endphp
    
    <div class="card" style="margin-bottom:24px; text-align:center; background:linear-gradient(135deg, var(--dark2), rgba(79,70,229,0.05))">
        @if(!$isGraded)
            <p style="font-size:13px; color:var(--text-muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:12px">Status Ujian</p>
            <div style="font-size:24px; font-weight:700; color:#F59E0B; line-height:1; margin:20px 0">
                Ujian telah dikumpulkan.<br>
                <span style="font-size:16px; font-weight:500; color:var(--text-muted); display:block; margin-top:8px">Menunggu konfirmasi nilai dari guru.</span>
            </div>
        @else
            <p style="font-size:13px; color:var(--text-muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:12px">Skor Akhir Anda</p>
            <div style="font-size:72px; font-weight:900; color:{{ $session->score >= 70 ? '#34D399' : '#F87171' }}; line-height:1">
                {{ number_format($session->score, 0) }}
            </div>
            <div style="margin-top:16px"><span class="badge {{ $session->score >= 70 ? 'badge-green' : 'badge-red' }}" style="padding:8px 24px; font-size:16px; font-weight:800">GRADE {{ $session->grade }}</span></div>
        @endif
        
        @if($isGraded)
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:12px; margin-top:40px; padding-top:24px; border-top:1px solid var(--border)">
            <div>
                <div style="font-size:11px; color:var(--text-muted); margin-bottom:4px">BENAR</div>
                <div style="font-size:18px; font-weight:700; color:#34D399">{{ $session->answers->where('is_correct', true)->count() }}</div>
            </div>
            <div>
                <div style="font-size:11px; color:var(--text-muted); margin-bottom:4px">SALAH</div>
                <div style="font-size:18px; font-weight:700; color:#F87171">{{ $session->answers->where('is_correct', false)->count() }}</div>
            </div>
            <div>
                <div style="font-size:11px; color:var(--text-muted); margin-bottom:4px">MENUNGGU PENILAIAN</div>
                <div style="font-size:18px; font-weight:700; color:#F59E0B">0</div>
            </div>
            <div>
                <div style="font-size:11px; color:var(--text-muted); margin-bottom:4px">TOTAL POIN</div>
                <div style="font-size:18px; font-weight:700">{{ $session->earned_points }} / {{ $session->total_points }}</div>
            </div>
        </div>
        @endif
    </div>

    {{-- Answers Review --}}
    @if($isGraded)
    <h3 style="font-size:16px; font-weight:700; margin-bottom:16px">Tinjauan Jawaban</h3>
    @foreach($session->answers as $index => $answer)
    <div class="card" style="margin-bottom:16px; border-left:4px solid var(--border)">
        <div style="display:flex; justify-content:space-between; margin-bottom:12px">
            <span style="font-size:12px; font-weight:700; color:var(--text-muted)">SOAL #{{ $index + 1 }}</span>
            <span class="badge" style="background:rgba(156,163,175,0.2); border:1px solid rgba(156,163,175,0.3); color:#9CA3AF">
                JAWABAN SUDAH DIKIRIM
            </span>
        </div>
        
        <p style="font-weight:600; font-size:15px; margin-bottom:16px">{{ $answer->question->question_text }}</p>

        @if($answer->question->question_type === 'multiple_choice')
            <div style="display:grid; grid-template-columns:1fr; gap:8px">
                @foreach($answer->question->options as $option)
                    <div style="padding:10px 14px; border-radius:8px; font-size:13px; display:flex; align-items:center; gap:10px; 
                        @if($option->is_correct)
                            background:rgba(16,185,129,0.15); border:1px solid rgba(16,185,129,0.3); color:#34D399;
                        @elseif($answer->option_id && $answer->option_id === $option->id)
                            background:rgba(239,68,68,0.15); border:1px solid rgba(239,68,68,0.3); color:#F87171;
                        @else
                            background:rgba(255,255,255,0.02); border:1px solid var(--border); color:var(--text-muted);
                        @endif
                    ">
                        @if($option->is_correct)
                            <i class="fas fa-check-circle"></i>
                        @elseif($answer->option_id && $answer->option_id === $option->id)
                            <i class="fas fa-times-circle"></i>
                        @else
                            <i class="far fa-circle"></i>
                        @endif
                        {{ $option->option_text }}
                        @if($option->is_correct)
                            <span style="margin-left:auto; font-size:11px; font-weight:600">BENAR</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            {{-- Essay Answer Display --}}
            @if($answer->grading_status === 'pending')
                <div style="padding:16px; background:rgba(255,255,255,0.02); border:1px solid var(--border); border-radius:8px; font-size:14px; line-height:1.6; color:var(--text-muted); font-style:italic">
                    Jawaban telah dikirim dan akan dinilai oleh guru
                </div>
            @else
                <div style="margin-bottom:12px">
                    <div style="font-size:12px; color:var(--text-muted); margin-bottom:6px">Jawaban Anda:</div>
                    <div style="padding:12px; background:rgba(255,255,255,0.02); border:1px solid var(--border); border-radius:8px; font-size:14px; line-height:1.6">
                        {{ $answer->essay_answer ?? 'Tidak ada jawaban' }}
                    </div>
                </div>
                @if($answer->essay_feedback)
                <div style="margin-bottom:12px">
                    <div style="font-size:12px; color:var(--text-muted); margin-bottom:6px">Feedback Guru:</div>
                    <div style="padding:12px; background:rgba(79,70,229,0.1); border:1px solid rgba(79,70,229,0.2); border-radius:8px; font-size:14px; line-height:1.6">
                        {{ $answer->essay_feedback }}
                    </div>
                </div>
                @endif
                <div style="display:flex; gap:12px; align-items:center">
                    <div style="font-size:12px; color:var(--text-muted)">Skor:</div>
                    <div style="font-size:16px; font-weight:700; color:#818CF8">{{ $answer->points_earned ?? 0 }} / {{ $answer->question->points ?? 0 }}</div>
                </div>
            @endif
        @endif
    </div>
    @endforeach
    @endif
</div>
@endsection
