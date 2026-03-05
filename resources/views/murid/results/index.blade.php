@extends('layouts.app')
@section('page-title', 'Nilai & Riwayat Ujian')

@section('content')
<div class="card">
    <h3 style="font-size:17px; font-weight:700; margin-bottom:24px"><i class="fas fa-trophy" style="color:#FCD34D; margin-right:8px"></i>Pencapaian Anda</h3>

    @if($sessions->isEmpty())
        <div style="text-align:center; padding:60px; color:var(--text-muted)">
            <i class="fas fa-award" style="font-size:48px; margin-bottom:16px; display:block; color:#334155"></i>
            <p>Anda belum memiliki riwayat nilai. Selesaikan ujian untuk melihat hasil di sini.</p>
            <a href="{{ route('murid.exams.index') }}" class="btn btn-green" style="margin-top:20px">Lihat Ujian</a>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>UJIAN</th>
                        <th>TANGGAL KERJA</th>
                        <th>SKOR</th>
                        <th>GRADE</th>
                        <th style="text-align:right">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                    @php
                        $hasPendingGrading = $session->answers->contains(function($answer) {
                            return $answer->grading_status === 'pending';
                        });
                    @endphp
                    <tr>
                        <td>
                            <div style="font-weight:600">{{ $session->exam->title }}</div>
                            <div style="font-size:11px; color:var(--text-muted)">{{ $session->total_points }} Total Poin</div>
                        </td>
                        <td>{{ $session->submitted_at->format('d M Y, H:i') }}</td>
                        @if($hasPendingGrading)
                        <td>
                            <div style="font-size:18px; font-weight:800; color:#9CA3AF">
                                Menunggu
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background:rgba(156,163,175,0.2); border:1px solid rgba(156,163,175,0.3); color:#9CA3AF">DINILAI</span>
                        </td>
                        @else
                        <td>
                            <div style="font-size:18px; font-weight:800; color:{{ $session->score >= 70 ? '#34D399' : '#F87171' }}">
                                {{ number_format($session->score, 0) }}
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $session->score >= 70 ? 'badge-green' : 'badge-red' }}">{{ $session->grade }}</span>
                        </td>
                        @endif
                        <td style="text-align:right">
                            <a href="{{ route('murid.results.show', $session) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-eye"></i> Detail
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
