@extends('layouts.app')
@section('page-title', 'Kelola Soal: ' . $exam->title)

@section('topbar-actions')
   <a href="{{ route('guru.exams.questions.create', $exam) }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Tambah Soal
    </a>
@endsection

@section('content')
<div class="card">
    <a href="{{ route('guru.exams.index') }}" class="btn btn-secondary btn-sm" style="margin-bottom:20px">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Ujian
    </a>

    @if($questions->isEmpty())
        <div style="text-align:center;padding:60px;color:#64748B">
            <i class="fas fa-question-circle" style="font-size:48px;margin-bottom:16px;display:block;color:#334155"></i>
            <p style="font-size:16px;font-weight:600;margin-bottom:8px">Belum ada soal</p>
            <p style="font-size:14px;margin-bottom:20px">Tambahkan soal pertama untuk ujian ini</p>
            <div style="display:flex; gap:10px; justify-content:center">               
            <a href="{{ route('guru.exams.questions.bulk-create', $exam) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Buat Soal
                </a>
            </div>
        </div>
    @else
        @foreach($questions as $index => $question)
            <div style="padding:20px; border:1px solid var(--border); border-radius:12px; margin-bottom:16px; background:rgba(255,255,255,0.01)">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px">
                    <div style="display:flex; gap:12px">
                        <div style="width:28px; height:28px; background:var(--guru-primary); border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; flex-shrink:0">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <div style="font-weight:600; font-size:15px; line-height:1.5">{!! nl2br(e($question->question_text)) !!}</div>
                            <div style="margin-top:8px">
                                <span class="badge badge-blue">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span>
                                <span class="badge badge-yellow">{{ $question->points }} Poin</span>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; gap:8px">
                        <a href="{{ route('guru.exams.questions.edit', [$exam, $question]) }}" class="btn btn-secondary btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('guru.exams.questions.destroy', [$exam, $question]) }}" class="delete-form">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm btn-delete" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                @if($question->question_type === 'multiple_choice')
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-top:16px; padding-left:40px">
                        @foreach($question->options as $option)
                            <div style="padding:10px 14px; border-radius:8px; font-size:13px; display:flex; align-items:center; gap:10px; {{ $option->is_correct ? 'background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); color:#34D399' : 'background:rgba(255,255,255,0.03); border:1px solid var(--border); color:var(--text-muted)' }}">
                                <i class="fas {{ $option->is_correct ? 'fa-check-circle' : 'fa-circle' }}" style="font-size:12px"></i>
                                {{ $option->option_text }}
                                @if($option->is_correct)
                                    <span style="font-size:10px; font-weight:700; margin-left:auto; text-transform:uppercase">(Kunci)</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</div>

<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            Swal.fire({
                title: 'Hapus Soal?',
                text: "Soal ini akan dihapus permanen dari ujian ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                background: '#1E293B',
                color: '#F1F5F9'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
