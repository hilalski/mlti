@extends('layouts.app')
@section('title', 'Laporan SIGAP | MLTI')
@section('content')
    <div class="pagetitle">
        <h1>Laporan SIGAP</h1>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Pelapor</th>
                                <th>Lokasi</th>
                                <th>Kerusakan</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($complaints as $item)
                                <tr>
                                    <td>{{ $item->reporter->name ?? '-' }}</td>
                                    <td>{{ $item->location }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ ucfirst($item->status) }}</td>
                            </tr>@empty<tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada laporan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>{{ $complaints->links() }}
            </div>
        </div>
    </section>
@endsection
