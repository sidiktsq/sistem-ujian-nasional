@extends('layouts.app')
@section('page-title', 'Detail Jawaban: ' . $session->user->name)

@section('content')
<div>
    <a href="{{ route('guru.exams.results.index', $exam) }}" class="btn btn-secondary btn-sm" style="margin-bottom:20px">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Hasil
    </a>

    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:24px; align-items:start">
        {{-- Left: Answers Detail --}}
        <div>
            @foreach($session->answers as $index => $answer)
            <div class="card" style="margin-bottom:16px; border-left:4px solid {{ ($answer->question->question_type === 'essay' && $answer->grading_status === 'pending') ? '#F59E0B' : ($answer->is_correct ? '#34D399' : '#F87171') }}">
                <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:12px">
                    <span style="font-size:12px; font-weight:700; color:var(--text-muted)">PERNYATAAN #{{ $index + 1 }}</span>
                    @if($answer->question->question_type === 'essay')
                        <span class="badge {{ $answer->grading_status === 'pending' ? 'badge-yellow' : ($answer->is_correct ? 'badge-green' : 'badge-red') }}" style="{{ $answer->grading_status === 'pending' ? 'background:rgba(245,158,11,0.2); border:1px solid rgba(245,158,11,0.5); color:#FCD34D' : '' }}">
                            @if($answer->grading_status === 'pending')
                                MENUNGGU PENILAIAN
                            @else
                                {{ $answer->is_correct ? 'BENAR' : 'SALAH' }} (+{{ $answer->points_earned }} Poin)
                            @endif
                        </span>
                    @else
                        <span class="badge {{ $answer->is_correct ? 'badge-green' : 'badge-red' }}">
                            {{ $answer->is_correct ? 'BENAR' : 'SALAH' }} (+{{ $answer->points_earned }} Poin)
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
                                @if($option->id == $answer->option_id)
                                    <span style="font-size:10px; font-weight:700; margin-left:auto">(Jawaban Murid)</span>
                                @elseif($option->is_correct)
                                    <span style="font-size:10px; font-weight:700; margin-left:auto">(Kunci)</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Essay Answer with Grading Form --}}
                    <div style="padding:16px; background:rgba(255,255,255,0.02); border:1px solid var(--border); border-radius:8px; font-size:14px; line-height:1.6; margin-bottom:16px">
                        {!! nl2br(e($answer->essay_answer)) !!}
                    </div>

                    @if($answer->grading_status === 'pending')
                    <form method="POST" action="{{ route('guru.exams.results.grade-essay', [$exam, $session, $answer]) }}" style="padding-top:16px; border-top:1px solid var(--border)" id="essay-grade-{{ $answer->id }}">
                        @csrf
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px">
                            <div>
                                <label style="font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase; display:block; margin-bottom:6px">Hasil Penilaian</label>
                                <select name="is_correct" class="form-control" required onchange="updatePoints(this, {{ $answer->question->points }})">
                                    <option value="">-- Pilih --</option>
                                    <option value="1">Benar ✓</option>
                                    <option value="0">Salah ✗</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase; display:block; margin-bottom:6px">Poin (Max: {{ $answer->question->points }})</label>
                                <input type="number" name="points_earned" class="form-control points-input" min="0" max="{{ $answer->question->points }}" value="0" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-green btn-sm" style="width:100%">
                            <i class="fas fa-check"></i> Simpan Penilaian
                        </button>
                    </form>
                    @else
                    <div style="padding:12px; background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.3); border-radius:8px; margin-top:12px">
                        <p style="font-size:13px; margin-bottom:8px"><strong>Status:</strong> Sudah dinilai</p>
                        <p style="font-size:13px; margin-bottom:4px"><strong>Hasil:</strong> {{ $answer->is_correct ? 'Benar ✓' : 'Salah ✗' }} - <strong>{{ $answer->points_earned }}/{{ $answer->question->points }}</strong> Poin</p>
                        <p style="font-size:11px; color:var(--text-muted)">Dinilai oleh {{ $answer->gradedBy->name ?? 'System' }} pada {{ $answer->graded_at ? $answer->graded_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                    @endif
                @endif
            </div>
            @endforeach
        </div>

        {{-- Right: Student Info --}}
        <div class="sidebar-sticky" style="position:sticky; top:80px">
            <div class="card">
                <h4 style="font-size:14px; font-weight:700; margin-bottom:20px; text-transform:uppercase; letter-spacing:1px">Ringkasan Nilai</h4>
                
                <div style="text-align:center; padding:20px 0; border-bottom:1px solid var(--border); margin-bottom:20px">
                    <div style="font-size:48px; font-weight:800; color:{{ $session->score >= 70 ? '#34D399' : '#F87171' }}; line-height:1">{{ number_format($session->score, 0) }}</div>
                    <div style="margin-top:10px"><span class="badge {{ $session->score >= 70 ? 'badge-green' : 'badge-red' }}" style="padding:6px 16px; font-size:14px">{{ $session->grade }}</span></div>
                </div>

                <div style="display:grid; grid-template-columns:1fr; gap:16px">
                    <div style="display:flex; justify-content:space-between">
                        <span style="font-size:13px; color:var(--text-muted)">Murid:</span>
                        <span style="font-size:13px; font-weight:600">{{ $session->user->name }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between">
                        <span style="font-size:13px; color:var(--text-muted)">Mulai:</span>
                        <span style="font-size:13px; font-weight:600">{{ $session->started_at->format('H:i') }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between">
                        <span style="font-size:13px; color:var(--text-muted)">Submit:</span>
                        <span style="font-size:13px; font-weight:600">{{ $session->submitted_at->format('H:i') }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between">
                        <span style="font-size:13px; color:var(--text-muted)">Durasi:</span>
                        <span style="font-size:13px; font-weight:600">{{ $session->started_at->diffForHumans($session->submitted_at, true) }}</span>
                    </div>
                    <hr style="border:none; border-top:1px solid var(--border)">
                    <div style="display:flex; justify-content:space-between">
                        <span style="font-size:13px; color:var(--text-muted)">Poin Benar:</span>
                        <span style="font-size:13px; font-weight:700; color:#34D399">{{ $session->earned_points }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between">
                        <span style="font-size:13px; color:var(--text-muted)">Total Poin:</span>
                        <span style="font-size:13px; font-weight:600">{{ $session->total_points }}</span>
                    </div>

                    @php
                        $isFullyGraded = !$session->answers->contains('grading_status', 'pending');
                        $hasPendingEssays = $session->answers->where('grading_status', 'pending')->where('question.question_type', 'essay')->count() > 0;
                    @endphp

                    @if(!$isFullyGraded)
                        <div style="margin-top:20px; padding-top:20px; border-top:1px solid var(--border)">
                            @if($hasPendingEssays)
                                <div style="padding:10px; background:rgba(245,158,11,0.1); border:1px solid rgba(245,158,11,0.2); border-radius:8px; margin-bottom:12px; font-size:12px; color:#FCD34D">
                                    <i class="fas fa-exclamation-triangle"></i> Selesaikan penilaian essay sebelum rilis nilai.
                                </div>
                            @endif
                            <form method="POST" action="{{ route('guru.exams.results.confirm', [$exam, $session]) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary" style="width:100%" {{ $hasPendingEssays ? 'disabled' : '' }}>
                                    <i class="fas fa-paper-plane"></i> Konfirmasi & Rilis Nilai
                                </button>
                            </form>
                        </div>
                    @else
                        <div style="margin-top:20px; padding:12px; background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); border-radius:8px; text-align:center; font-size:13px; color:#34D399">
                            <i class="fas fa-check-circle"></i> Nilai sudah dirilis
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updatePoints(select, maxPoints) {
        const form = select.closest('form');
        const pointsInput = form.querySelector('input[name="points_earned"]');
        
        if (select.value === '1') {
            // Jika dipilih "Benar", isi poin dengan nilai max
            pointsInput.value = maxPoints;
        } else if (select.value === '0') {
            // Jika dipilih "Salah", isi poin dengan 0
            pointsInput.value = 0;
        }
    }
</script>
@endsection
