@extends('layouts.app')
@section('page-title', 'Kelola Ujian')

@section('topbar-actions')
    <a href="{{ route('guru.exams.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Buat Ujian Baru
    </a>
@endsection

@section('content')
<div class="card">
    @if($exams->isEmpty())
        <div style="text-align:center;padding:60px;color:#64748B">
            <i class="fas fa-file-alt" style="font-size:48px;margin-bottom:16px;display:block;color:#334155"></i>
            <p style="font-size:16px;font-weight:600;margin-bottom:8px">Belum ada ujian</p>
            <p style="font-size:14px;margin-bottom:20px">Mulai dengan membuat ujian pertama Anda</p>
            <a href="{{ route('guru.exams.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Ujian Pertama
            </a>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Ujian</th><th>Soal</th><th>Durasi</th><th>Status</th><th>Peserta</th><th style="text-align:right">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($exams as $exam)
                    <tr>
                        <td>
                            <div style="font-weight:600;font-size:15px">{{ $exam->title }}</div>
                            @if($exam->description)
                                <div style="font-size:12px;color:#64748B;margin-top:2px">{{ Str::limit($exam->description, 60) }}</div>
                            @endif
                        </td>
                        <td><span class="badge badge-blue">{{ $exam->questions_count }} soal</span></td>
                        <td><i class="fas fa-clock" style="color:#64748B;margin-right:4px"></i>{{ $exam->duration }} mnt</td>
                        <td>
                            @if($exam->is_active)
                                <span class="badge badge-green"><i class="fas fa-circle" style="font-size:8px"></i> Aktif</span>
                            @else
                                <span class="badge badge-gray">Nonaktif</span>
                            @endif
                        </td>
                        <td><span class="badge badge-yellow">{{ $exam->submitted_count }} selesai</span></td>
                        <td style="text-align:right">
                            <div style="display:flex;gap:6px;justify-content:flex-end;flex-wrap:wrap">
                                <a href="{{ route('guru.exams.questions.index', $exam) }}" class="btn btn-secondary btn-sm" title="Kelola Soal">
                                    <i class="fas fa-list"></i> Soal
                                </a>
                                <a href="{{ route('guru.exams.results.index', $exam) }}" class="btn btn-secondary btn-sm" title="Hasil">
                                    <i class="fas fa-chart-bar"></i>
                                </a>
                                <a href="{{ route('guru.exams.edit', $exam) }}" class="btn btn-secondary btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('guru.exams.destroy', $exam) }}" onsubmit="return confirm('Hapus ujian ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $exams->links() }}</div>
    @endif
</div>
@endsection
