@extends('layouts.app')
@section('page-title', 'Hasil Ujian: ' . $exam->title)

@section('content')
<div class="card">
    <a href="{{ route('guru.exams.index') }}" class="btn btn-secondary btn-sm" style="margin-bottom:20px">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Ujian
    </a>

    <div style="margin-bottom:24px; display:flex; justify-content:space-between; align-items:flex-end">
        <div>
            <h3 style="font-size:16px; font-weight:700">{{ $exam->title }}</h3>
            <p style="font-size:13px; color:var(--text-muted); margin-top:4px">Daftar murid yang telah mengerjakan ujian.</p>
        </div>
        <div style="text-align:right">
            <span class="badge badge-blue">{{ $sessions->total() }} Murid Mengerjakan</span>
        </div>
    </div>

    @if($sessions->isEmpty())
        <div style="text-align:center;padding:60px;color:#64748B">
            <i class="fas fa-users-slash" style="font-size:48px;margin-bottom:16px;display:block;color:#334155"></i>
            <p style="font-size:16px;font-weight:600;margin-bottom:8px">Belum ada hasil</p>
            <p style="font-size:14px">Belum ada murid yang mengumpulkan jawaban untuk ujian ini.</p>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>NAMA MURID</th>
                        <th>WAKTU SUBMIT</th>
                        <th>POIN / TOTAL</th>
                        <th>SKOR AKHIR</th>
                        <th style="text-align:right">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                    <tr>
                        <td>
                            <div style="font-weight:600">{{ $session->user->name }}</div>
                            <div style="font-size:11px; color:var(--text-muted)">{{ $session->user->email }}</div>
                        </td>
                        <td>
                            <div style="font-size:13px">{{ $session->submitted_at->format('d M Y, H:i') }}</div>
                        </td>
                        <td>
                            <span style="font-weight:500">{{ $session->earned_points }}</span> / {{ $session->total_points }}
                        </td>
                        <td>
                            <div style="display:flex; align-items:center; gap:8px">
                                <div style="font-size:18px; font-weight:800; color:{{ $session->score >= 70 ? '#34D399' : '#F87171' }}">{{ number_format($session->score, 0) }}</div>
                                <span class="badge {{ $session->score >= 70 ? 'badge-green' : 'badge-red' }}">{{ $session->grade }}</span>
                            </div>
                        </td>
                        <td style="text-align:right">
                            <a href="{{ route('guru.exams.results.show', [$exam, $session]) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-eye"></i> Detail Jawaban
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $sessions->links() }}</div>
    @endif
</div>
@endsection
