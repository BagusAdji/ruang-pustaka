@extends('admin.layout')
@section('page-title', 'Detail Peminjaman')
@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/detailpeminjaman.css') }}">

    <div class="loan-container">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    Transaksi #{{ $peminjaman->id }}
                    @if ($peminjaman->status == 'booking')
                        <span
                            style="background: #FEF3C7; color: #D97706; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem;">
                            <i class="fas fa-clock"></i> BOOKING
                        </span>
                    @elseif($peminjaman->status == 'dipinjam')
                        <span
                            style="background: #DBEAFE; color: #1E40AF; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem;">
                            DIPINJAM
                        </span>
                    @elseif($peminjaman->status == 'terlambat')
                        <span class="badge-danger"
                            style="background: #FEE2E2; color: #DC2626; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem;">
                            <i class="fas fa-exclamation-circle"></i> TERLAMBAT
                        </span>
                    @elseif($peminjaman->status == 'dikembalikan')
                        <span
                            style="background: #DCFCE7; color: #166534; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem;">
                            Buku Sudah Dikembalikan
                        </span>
                    @elseif($peminjaman->status == 'batal')
                        <span
                            style="background: #F3F4F6; color: #374151; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem;">
                            DIBATALKAN
                        </span>
                    @endif
                </h1>
                <p style="color: var(--text-gray); margin-top: 5px;">Lihat dan kelola detail transaksi peminjaman ini.</p>
            </div>

            <div class="header-actions">
                {{-- <a href="mailto:{{ $peminjaman->users->email }}" class="btn btn-white">
                    <i class="far fa-envelope"></i> Hubungi User
                </a> --}}

                @if ($peminjaman->status == 'booking')
                    <form action="{{ route('admin.peminjaman.approve', $peminjaman->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Setujui peminjaman ini?')">
                            <i class="fas fa-check"></i> Setujui Peminjaman
                        </button>
                    </form>
                @endif
                @php
                    $isLate = now()
                        ->startOfDay()
                        ->greaterThan(\Carbon\Carbon::parse($peminjaman->tanggal_pengembalian)->startOfDay());
                @endphp
                @if ($peminjaman->status == 'dipinjam' && !$isLate)
                    <form action="{{ route('admin.peminjaman.complete', $peminjaman->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-primary"
                            onclick="return confirm('Apakah buku fisik sudah diterima kembali?')">
                            <i class="fas fa-undo"></i> Terima Pengembalian Buku
                        </button>
                    </form>
                @endif
                @if ($peminjaman->status == 'dipinjam' && $isLate)
                        <form action="{{ route('admin.peminjaman.denda', $peminjaman->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn" style="background-color: #DC2626; color: white;">
                                <i class="fas fa-calculator"></i> Hitung Denda
                            </button>
                        </form>
                @endif

                @if ($peminjaman->status == 'terlambat')
                    <form action="{{ route('admin.peminjaman.complete', $peminjaman->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-primary"
                            onclick="return confirm('Buku dikembalikan dan denda sudah lunas?')">
                            <i class="fas fa-check-double"></i> Selesaikan (Lunas & Kembali)
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="content-grid">
            <div class="left-col">

                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-book" style="margin-right: 8px;"></i> Detail Buku</span>
                    </div>

                    <div class="book-info-wrapper">
                        <img src="{{ $peminjaman->buku->cover ? asset('storage/' . $peminjaman->buku->cover) : 'https://via.placeholder.com/150x220?text=No+Cover' }}"
                            alt="Cover" class="book-cover">

                        <div class="book-meta">
                            <div class="book-title">
                                <h3>{{ $peminjaman->buku->judul_buku }}</h3>
                                <span
                                    class="book-category">{{ $peminjaman->buku->kategori->nama_kategori ?? 'Umum' }}</span>
                            </div>

                            <div class="meta-item">
                                <label>Penulis</label>
                                <p>{{ $peminjaman->buku->penulis }}</p>
                            </div>

                            <div class="meta-item">
                                <label>ISBN</label>
                                <p>{{ $peminjaman->buku->id ?? '-' }}</p>
                            </div>

                            <div class="meta-item">
                                <label>Penerbit</label>
                                <p>{{ $peminjaman->buku->penerbit }}</p>
                            </div>

                            <div class="meta-item">
                                <label>Lokasi Rak</label>
                                <p><i class="fas fa-map-marker-alt text-primary"></i> Rak A-12</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-chart-line" style="margin-right: 8px;"></i> Linimasa Status</span>
                    </div>

                    <div class="timeline-track">
                        <div class="timeline-step">
                            <div class="step-icon {{ in_array($peminjaman->status, ['dipinjam', 'terlambat', 'dikembalikan']) ? 'active' : '' }}"
                                style="{{ $peminjaman->status == 'booking' ? 'background: #FEF3C7; color: #D97706; border-color: #FEF3C7;' : '' }}">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <span class="step-date">
                                {{ $peminjaman->tanggal_peminjaman ? \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d M Y') : 'Menunggu' }}
                            </span>
                            <span class="step-label">MULAI PINJAM</span>
                        </div>

                        <div class="timeline-step">
                            <div
                                class="step-icon {{ $peminjaman->status == 'terlambat' ? 'danger' : (in_array($peminjaman->status, ['dipinjam', 'dikembalikan']) ? 'active' : '') }}">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <span class="step-date {{ $peminjaman->status == 'terlambat' ? 'text-danger' : '' }}">
                                {{ $peminjaman->tanggal_pengembalian ? \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian)->format('d M Y') : '-' }}
                            </span>
                            <span class="step-label">JATUH TEMPO</span>
                        </div>

                        <div class="timeline-step">
                            <div class="step-icon {{ $peminjaman->tanggal_dikembalikan ? 'active' : '' }}"
                                style="background: {{ $peminjaman->tanggal_dikembalikan ? '' : '#f1f5f9' }}; color: {{ $peminjaman->tanggal_dikembalikan ? '' : '#cbd5e1' }}">
                                <i class="fas fa-undo"></i>
                            </div>
                            <span class="step-date">
                                {{ $peminjaman->tanggal_dikembalikan ? \Carbon\Carbon::parse($peminjaman->tanggal_dikembalikan)->format('d M Y') : '-' }}
                            </span>
                            <span class="step-label">DIKEMBALIKAN</span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-history" style="margin-right: 8px;"></i> Log Aktivitas</span>
                    </div>
                    <div style="font-size: 0.9rem; color: var(--text-gray);">
                        <div style="margin-bottom: 15px; display: flex; gap: 10px;">
                            <i class="fas fa-bell"></i>
                            <div>
                                <p style="margin: 0; color: var(--text-dark);">Transaksi dibuat (Booking).</p>
                                <small>{{ $peminjaman->created_at->format('d M Y, H:i') }} WIB</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-col">

                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-user" style="margin-right: 8px;"></i> Data Peminjam</span>
                    </div>

                    <div class="borrower-profile">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($peminjaman->users->nama) }}&background=random"
                            alt="User" class="avatar">
                        <div class="borrower-info">
                            <h4>{{ $peminjaman->users->nama }}</h4>
                            <span class="status-badge">Anggota Aktif</span>
                        </div>
                    </div>

                    <div class="contact-list">
                        <div class="contact-item">
                            <i class="fas fa-id-card fa-fw"></i>
                            <div>
                                <span class="contact-label">ID Anggota</span>
                                <strong>User-{{ $peminjaman->users->id }}</strong>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope fa-fw"></i>
                            <div>
                                <span class="contact-label">Email</span>
                                <span>{{ $peminjaman->users->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if (!in_array($peminjaman->status, ['booking', 'batal']))
                    <div class="card">
                        <div class="card-header">
                            <span><i class="fas fa-dollar-sign" style="margin-right: 8px;"></i> Keuangan</span>
                        </div>

                        @php
                            $nominalDenda = 0;
                            $statusBayar = 'AMAN'; // Default

                            if ($peminjaman->denda) {
                                $nominalDenda = $peminjaman->denda->jumlah_denda;
                                $statusBayar = $peminjaman->denda->status_denda; // Isi: 'belum_bayar' atau 'sudah_bayar'
                            }
                            // 2. Estimasi Visual (Jika belum ada di DB tapi telat)
                            elseif (
                                $peminjaman->status == 'dipinjam' &&
                                now()->greaterThan($peminjaman->tanggal_pengembalian)
                            ) {
                                $hariTelat = now()->diffInDays(
                                    \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian),
                                );
                                $nominalDenda = abs( $hariTelat * 1000);
                                $statusBayar = 'ESTIMASI';
                            }
                        @endphp

                        <div class="current-fine">
                            <span class="contact-label">Total Denda</span>
                            <div class="fine-amount">
                                Rp {{ number_format($nominalDenda, 0, ',', '.') }}

                                @if ($statusBayar == 'belum_bayar')
                                    <span class="badge-danger">BELUM BAYAR</span>
                                @elseif($statusBayar == 'sudah_bayar')
                                    <span
                                        style="background: #DCFCE7; color: #166534; font-size: 0.7rem; padding: 4px; border-radius: 4px;">LUNAS</span>
                                @elseif($statusBayar == 'ESTIMASI')
                                    <span
                                        style="background: #FEF3C7; color: #D97706; font-size: 0.7rem; padding: 4px; border-radius: 4px;">ESTIMASI</span>
                                @endif
                            </div>
                            <p class="fine-desc">Tarif denda: Rp 1.000 / hari.</p>
                        </div>

                        <div class="action-buttons-grid">
                            @if ($statusBayar == 'ESTIMASI')
                                <form action="{{ route('admin.peminjaman.denda', $peminjaman->id) }}" method="POST"
                                    style="width: 100%;">
                                    @csrf
                                    <button type="submit" class="btn"
                                        style="background: #DC2626; color: white; width: 100%;">
                                        <i class="fas fa-save"></i> Simpan Denda
                                    </button>
                                </form>
                            @elseif($statusBayar == 'belum_bayar')
                                <form action="{{ route('admin.peminjaman.complete', $peminjaman->id) }}" method="POST"
                                    style="width: 100%;"> @csrf @method('PATCH')
                                    <button type="submit" class="btn"
                                        style="background: #1E293B; color: white; width: 100%;">
                                        Tandai Sudah Bayar
                                    </button>
                                </form>
                            @elseif($statusBayar == 'sudah_bayar')
                                <button class="btn btn-white" disabled>Denda Lunas</button>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
