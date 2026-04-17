@extends('layouts.app')
@section('page-title', 'Mengerjakan Ujian: ' . $exam->title)

@section('topbar-actions')
    <div style="background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2); padding:6px 16px; border-radius:10px; display:flex; align-items:center; gap:10px">
        <i class="fas fa-clock" style="color:#F87171"></i>
        <span id="timer" style="font-family:monospace; font-size:18px; font-weight:800; color:#F87171">--:--</span>
    </div>
@endsection

@section('content')
<div style="max-width:900px; margin: 0 auto">
    <form id="exam-form" method="POST" action="{{ route('murid.exams.submit', [$exam, $session]) }}">
        @csrf
        
        @foreach($questions as $index => $question)
        <div class="card" style="margin-bottom:24px; scroll-margin-top:100px" id="q-{{ $question->id }}">
            <div style="display:flex; gap:16px; margin-bottom:20px">
                <div style="width:32px; height:32px; background:var(--murid-primary); border-radius:8px; display:flex; align-items:center; justify-content:center; font-weight:800; flex-shrink:0">
                    {{ $index + 1 }}
                </div>
                <div style="font-size:16px; font-weight:600; line-height:1.6">
                    {!! nl2br(e($question->question_text)) !!}
                </div>
            </div>

            @if($question->question_type === 'multiple_choice')
                <div style="display:grid; grid-template-columns:1fr; gap:12px; padding-left:48px">
                    @foreach($question->options as $option)
                    <label style="display:flex; align-items:center; gap:12px; padding:12px 16px; background:rgba(255,255,255,0.02); border:1px solid var(--border); border-radius:10px; cursor:pointer; transition:all 0.2s">
                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" style="width:18px; height:18px; accent-color:var(--murid-primary)">
                        <span style="font-size:14px">{{ $option->option_text }}</span>
                    </label>
                    @endforeach
                </div>
            @else
                <div style="padding-left:48px">
                    <textarea name="answers[{{ $question->id }}]" class="form-control" placeholder="Tuliskan jawaban Anda di sini..." style="min-height:150px"></textarea>
                </div>
            @endif
        </div>
        @endforeach

        <div class="card" style="text-align:center; padding:40px; background:linear-gradient(to bottom, var(--dark2), rgba(16,185,129,0.05))">
            <h3 style="font-size:18px; font-weight:700; margin-bottom:12px">Selesai Mengerjakan?</h3>
            <p style="color:var(--text-muted); font-size:14px; margin-bottom:24px">Pastikan semua pertanyaan telah dijawab sebelum mengumpulkan.</p>
            <button type="button" id="btn-submit-exam" class="btn btn-green btn-lg" style="padding:14px 40px; font-size:16px">
                <i class="fas fa-paper-plane"></i> KUMPULKAN JAWABAN
            </button>
        </div>
    </form>
</div>

{{-- Floating Navigation Quick Access --}}
<div style="position:fixed; left: calc(var(--sidebar-w) + 20px); bottom:20px; z-index:40; display:flex; gap:8px">
    {{-- Optional: could add question navigation dots here --}}
</div>
@endsection

@push('scripts')
<script>
    // Timer Logic
    const durationInMinutes = {{ $exam->duration }};
    const startTime = new Date("{{ $session->started_at->toIso8601String() }}").getTime();
    const endTime = startTime + (durationInMinutes * 60 * 1000);

    function updateTimer() {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance < 0) {
            document.getElementById("timer").innerHTML = "00:00";
            Swal.fire({
                title: 'Waktu Habis!',
                text: 'Waktu ujian telah habis. Jawaban Anda akan otomatis dikumpulkan.',
                icon: 'warning',
                confirmButtonText: 'Oke',
                allowOutsideClick: false,
                confirmButtonColor: '#10B981',
            }).then(() => {
                document.getElementById("exam-form").submit();
            });
            return;
        }

        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("timer").innerHTML = 
            (minutes < 10 ? "0" + minutes : minutes) + ":" + 
            (seconds < 10 ? "0" + seconds : seconds);

        // Warning color when under 5 minutes
        if (distance < (5 * 60 * 1000)) {
            document.getElementById("timer").parentElement.style.background = "rgba(239,68,68,0.2)";
        }
    }

    setInterval(updateTimer, 1000);
    updateTimer();

    // Prevent accidental navigation
    window.onbeforeunload = function() {
        return "Apakah Anda yakin ingin keluar? Progress pengerjaan mungkin tidak tersimpan.";
    };

    // Confirm submission with SweetAlert2
    document.getElementById("btn-submit-exam").addEventListener('click', function() {
        Swal.fire({
            title: 'Kumpulkan Jawaban?',
            text: "Pastikan semua pertanyaan telah dijawab sebelum mengumpulkan.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Kumpulkan',
            cancelButtonText: 'Batal',
            background: '#1E293B',
            color: '#F1F5F9'
        }).then((result) => {
            if (result.isConfirmed) {
                window.onbeforeunload = null;
                document.getElementById("exam-form").submit();
            }
        });
    });

    // Remove warning when submitting
    document.getElementById("exam-form").onsubmit = function() {
        window.onbeforeunload = null;
    };

    // Highlight selected options
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const container = this.closest('div');
            container.querySelectorAll('label').forEach(label => {
                label.style.background = 'rgba(255,255,255,0.02)';
                label.style.borderColor = 'var(--border)';
            });
            if (this.checked) {
                this.parentElement.style.background = 'rgba(16,185,129,0.1)';
                this.parentElement.style.borderColor = 'rgba(16,185,129,0.3)';
            }
        });
    });
</script>
@endpush
