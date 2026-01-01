@extends('admin.layout')

@push('css')
    <link rel="stylesheet" href="/css/admin/dashboard.css">
@endpush

@section('page-title', 'Overview Dashboard')

@section('content')
    <div class="container">

        <div class="card-wrapper">
            <div class="card card-blue">
                <div class="card-icon">
                    <span class="material-symbols-outlined">library_books</span>
                </div>
                <div class="card-info">
                    <div class="title">Total Koleksi</div>
                    <div class="value">{{ $total_buku }} <span class="unit">Buku</span></div>
                </div>
            </div>

            <div class="card card-orange">
                <div class="card-icon">
                    <span class="material-symbols-outlined">book_online</span>
                </div>
                <div class="card-info">
                    <div class="title">Sedang Dipinjam</div>
                    <div class="value">{{ $peminjaman_aktif }} <span class="unit">Sesi</span></div>
                </div>
            </div>

            <div class="card card-green">
                <div class="card-icon">
                    <span class="material-symbols-outlined">group</span>
                </div>
                <div class="card-info">
                    <div class="title">Total Anggota</div>
                    <div class="value">{{ $total_anggota }} <span class="unit">User</span></div>
                </div>
            </div>

             <div class="card card-red">
                <div class="card-icon">
                    <span class="material-symbols-outlined">attach_money</span>
                </div>
                <div class="card-info">
                    <div class="title">Total Denda Masuk</div>
                    <div class="value">Rp {{ number_format($total_denda, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">

            @if($peminjaman_terlambat > 0)
            <div class="alert-box">
                <span class="material-symbols-outlined icon-alert">warning</span>
                <div class="alert-text">
                    <h3>Perhatian!</h3>
                    <p>Ada <strong>{{ $peminjaman_terlambat }} buku</strong> yang belum dikembalikan melewati batas waktu.</p>
                </div>
                <a href="{{ route('admin.peminjaman.index') }}" class="btn-check">Cek Sekarang</a>
            </div>
            @endif

            <div class="table-peminjaman">
                <div class="peminjaman-header">
                    <h2>Peminjaman Terbaru</h2>
                    <a href="{{ route('admin.peminjaman.index') }}" class="view-all">Lihat Semua</a>
                </div>

                <div class="table-wrapper">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Anggota</th>
                                <th>Judul Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat_peminjaman as $index => $peminjaman)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="user-profile">
                                            <div class="avatar-placeholder">
                                                {{ substr($peminjaman->users->nama, 0, 1) }}
                                            </div>
                                            <span class="username">{{Str::limit($peminjaman->users->nama, 15) }}</span>
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ Str::limit($peminjaman->buku->judul_buku, 20) }}</td>
                                    <td class="text-muted">
                                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->translatedFormat('d M Y') }}
                                    </td>
                                    <td>
                                       {!! $peminjaman->status_badge !!}
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.peminjaman.detail', $peminjaman->id) }}" class="btn-icon">
                                            <span class="material-symbols-outlined">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
