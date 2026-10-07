@extends('layouts.app')
@section('title', 'Laporan Ruangan | MLTI')
@section('content')
    <div class="pagetitle">
        <h1>{{ $title ?? 'Laporan Ruangan' }}</h1>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Pemohon</th>
                                <th>Kegiatan</th>
                                <th>Ruangan</th>
                                <th>Jadwal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $item)
                                <tr>
                                    <td>{{ $item->requester->name ?? '-' }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->room->ruang ?? '-' }}</td>
                                    <td>{{ $item->starts_at->format('d M Y H:i') }}</td>
                                    <td>{{ ucfirst($item->status) }}</td>
                            </tr>@empty<tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada pengajuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>{{ $bookings->links() }}
            </div>
        </div>
    </section>
@endsection
