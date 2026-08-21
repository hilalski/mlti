@extends('layouts.app')

@title('Notifikasi Saya | MLTI-Report')

@section('content')
<div class="pagetitle">
  <h1>Notifikasi Laporan</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
      <li class="breadcrumb-item active">Notifikasi Laporan</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-10 col-md-12 mx-auto">

      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title my-0 text-white"><i class="bi bi-bell-fill me-1"></i> Notifikasi Belum Dibaca</h5>
        </div>

        <div class="card-body pt-3">
          
          <div class="list-group list-group-flush">
            @forelse($notifications as $notif)
              <div class="list-group-item list-group-item-action py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-start">
                  <div class="p-2 bg-light-warning rounded-circle me-3 text-warning">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                  </div>
                  <div>
                    <h6 class="mb-1 text-dark fw-bold">Laporan {{ ucfirst($notif->data['issue_type'] ?? 'Kerusakan') }} Baru</h6>
                    <p class="mb-1 text-muted small">
                      Pegawai <strong>{{ $notif->data['reporter_name'] ?? 'N/A' }}</strong> melaporkan kerusakan pada perangkat <strong>{{ $notif->data['device_series'] ?? 'N/A' }}</strong> (BMN: {{ $notif->data['device_id'] ?? '-' }}).
                    </p>
                    <blockquote class="bg-light p-2 rounded mb-1 small text-dark italic" style="border-left: 2px solid #ddd; font-style: italic;">
                      "{{ Str::limit($notif->data['description'] ?? '', 80) }}"
                    </blockquote>
                    <span class="text-muted small" style="font-size: 0.75rem;">
                      {{ \Carbon\Carbon::parse($notif->data['reported_at'] ?? now())->diffForHumans() }}
                    </span>
                  </div>
                </div>

                <div class="d-flex gap-2">
                  <a href="{{ route('admin.reports.show', $notif->data['report_id']) }}" class="btn btn-sm btn-outline-primary py-1">
                    <i class="bi bi-eye"></i> Detail
                  </a>
                  <form action="{{ route('admin.notifications.read', $notif->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-light py-1 text-success">
                      <i class="bi bi-check2-circle"></i> Tandai Dibaca
                    </button>
                  </form>
                </div>
              </div>
            @empty
              <div class="text-center py-5 text-muted">
                <i class="bi bi-bell-slash fs-1 d-block mb-2"></i>
                <h5>Tidak ada notifikasi baru</h5>
                <p class="mb-0 small">Seluruh laporan kerusakan masuk telah ditandai sebagai dibaca.</p>
              </div>
            @endforelse
          </div>

        </div>
      </div>

    </div>
  </div>
</section>
@endsection
