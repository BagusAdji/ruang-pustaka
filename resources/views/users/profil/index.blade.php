@extends('users.layout')

@section('title', 'Profil Saya - Ruang Pustaka')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/users/profil.css') }}">
@endpush

@section('content')
    <section class="section profile-section">
        <div class="container">
            <div class="profile-layout">

                <aside class="profile-sidebar">
                    <div class="profile-card sticky-card">
                        <div class="profile-header">

                            <form action="{{ route('user.profil.photo') }}" method="POST" enctype="multipart/form-data"
                                id="avatarForm">
                                @csrf

                                <div class="profile-avatar-large" style="position: relative; cursor: pointer;"
                                    onclick="document.getElementById('avatarInput').click()">

                                    {{-- LOGIKA TAMPILAN GAMBAR --}}
                                    @if (Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar"
                                            style="object-fit: cover;">
                                    @else
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}"
                                            alt="Avatar">
                                    @endif

                                    {{-- OVERLAY KAMERA (Muncul saat hover) --}}
                                    <div class="avatar-overlay">
                                        <i data-lucide="camera" style="color: white;"></i>
                                    </div>
                                </div>

                                {{-- INPUT FILE TERSEMBUNYI --}}
                                <input type="file" name="avatar" id="avatarInput" style="display: none;"
                                    accept="image/*" onchange="document.getElementById('avatarForm').submit()">

                            </form>

                            <h2 class="profile-name-large">{{ $user->name }}</h2>
                            <p class="profile-status">Anggota Aktif</p>
                        </div>

                        <div class="profile-details">
                            <div class="detail-item">
                                <label>Username</label>
                                <div class="detail-value">
                                    <i data-lucide="at-sign"></i> {{ $user->nama ?? Str::slug($user->nama) }}
                                </div>
                            </div>
                            <div class="detail-item">
                                <label>Email</label>
                                <div class="detail-value">
                                    <i data-lucide="mail"></i> {{ $user->email }}
                                </div>
                            </div>

                            <div class="detail-item">
                                <label>Member ID</label>
                                <div class="detail-value">
                                    <i data-lucide="credit-card"></i> LIB-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                                </div>
                            </div>
                        </div>

                        <div class="profile-actions">
                            {{-- <button class="btn btn-white full-width" style="border: 1px solid #e2e8f0;">
                                <i data-lucide="settings" style="width: 16px;"></i> Edit Profil
                            </button> --}}

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger full-width">
                                    <i data-lucide="log-out" style="width: 16px;"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </aside>

                <main class="profile-content">

                    <div class="stats-row">
                        <div class="stat-card-profile">
                            <span class="stat-label">Sedang Dipinjam</span>
                            <div class="stat-value-row">
                                <span class="stat-num text-primary">{{ $sedangDipinjam }}</span>
                                <i data-lucide="book-open" class="stat-icon"></i>
                            </div>
                        </div>
                        <div class="stat-card-profile">
                            <span class="stat-label">Total Riwayat</span>
                            <div class="stat-value-row">
                                <span class="stat-num text-dark">{{ $totalRiwayat }}</span>
                                <i data-lucide="history" class="stat-icon"></i>
                            </div>
                        </div>
                        <div class="stat-card-profile">
                            @if ($statusDenda == 'belum_bayar')
                                {{-- Tampilan jika BELUM BAYAR (Merah/Warning) --}}
                                <span class="stat-label text-red-500">Belum Dibayar</span>
                                <div class="stat-value-row">
                                    <span class="stat-num text-red-600" style="color: rgb(215, 2, 2)">Rp
                                        {{ number_format($totalDenda, 0, ',', '.') }}</span>
                                    <i data-lucide="alert-circle" class="stat-icon icon-red" style="color: rgb(215, 2, 2)"></i>
                                </div>
                            @elseif ($statusDenda == 'sudah_bayar')
                                {{-- Tampilan jika SUDAH BAYAR (Hijau/Sukses) --}}
                                <span class="stat-label text-gray-500"  >Denda Lunas</span>
                                <div class="stat-value-row">
                                    <span class="stat-num text-green" >Rp
                                        {{ number_format($totalDenda, 0, ',', '.') }}</span>
                                    <i data-lucide="check-circle" class="stat-icon icon-green"></i>
                                </div>
                            @endif
                            <span class="stat-label text-gray-500"  >Denda Lunas</span>
                                <div class="stat-value-row">
                                    <span class="stat-num text-green" >Rp
                                        {{ number_format($totalDenda, 0, ',', '.') }}</span>
                                    <i data-lucide="check-circle" class="stat-icon icon-green"></i>
                                </div>
                        </div>
                    </div>

                    <div class="loans-container">
                        <div class="tabs-header">
                            <button class="tab-btn active" onclick="openTab('active-loans', this)">Peminjaman Aktif</button>
                            <button class="tab-btn" onclick="openTab('history-loans', this)">Riwayat Pengembalian</button>
                        </div>

                        <div class="tab-content">

                            <div id="active-loans" class="tab-pane active">
                                <div class="loan-list">
                                    @forelse($peminjamanAktif as $item)
                                        @php
                                            // Hitung sisa hari
                                            $tglKembali = \Carbon\Carbon::parse($item->tanggal_pengembalian);
                                            $sisaHari = now()->diffInDays($tglKembali, false);
                                            $isTerlambat = $sisaHari < 0;
                                        @endphp

                                        <div class="loan-card" style="{{ $isTerlambat ? 'border-color: #ef4444;' : '' }}">
                                            <div class="loan-img">
                                                @if ($item->buku->cover)
                                                    <img src="{{ asset('storage/' . $item->buku->cover) }}" alt="Cover">
                                                @else
                                                    <img src="https://via.placeholder.com/70x100" alt="Cover">
                                                @endif
                                            </div>
                                            <div class="loan-info">
                                                <div class="loan-header">
                                                    <h4>{{ $item->buku->judul_buku }}</h4>

                                                    @if ($item->status == 'booking')
                                                        <span class="badge-days"
                                                            style="background: #fef3c7; color: #d97706;">Booking</span>
                                                    @elseif($isTerlambat)
                                                        <span class="badge-days"
                                                            style="background: #fee2e2; color: #ef4444;">Terlambat
                                                            {{ abs(intval($sisaHari)) }} Hari</span>
                                                    @else
                                                        <span class="badge-days">Sisa {{ intval($sisaHari) }} Hari</span>
                                                    @endif
                                                </div>
                                                <p class="loan-author">{{ $item->buku->penulis }}</p>

                                                <div class="loan-dates">
                                                    <div class="date-item">
                                                        <span>Dipinjam</span>
                                                        <strong>{{ \Carbon\Carbon::parse($item->tanggal_peminjaman)->format('d M Y') }}</strong>
                                                    </div>
                                                    <div class="date-divider"></div>
                                                    <div class="date-item">
                                                        <span>Tenggat</span>
                                                        <strong class="{{ $isTerlambat ? 'text-danger' : '' }}">
                                                            {{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d M Y') }}
                                                        </strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{ route('user.buku.show', $item->buku->id) }}"
                                                class="btn-detail">Detail</a>
                                        </div>
                                    @empty
                                        <div style="text-align: center; padding: 40px; color: #64748b;">
                                            <i data-lucide="book-open"
                                                style="width: 40px; height: 40px; margin-bottom: 10px; opacity: 0.5;"></i>
                                            <p>Tidak ada peminjaman aktif saat ini.</p>
                                            <a href="{{ route('user.koleksi') }}" class="btn btn-primary"
                                                style="margin-top: 15px;">Mulai Pinjam</a>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div id="history-loans" class="tab-pane">
                                <div class="history-list">
                                    @forelse($riwayatPeminjaman as $item)
                                        <div class="history-item">
                                            <div class="h-book">
                                                @if ($item->buku->cover)
                                                    <img src="{{ asset('storage/' . $item->buku->cover) }}"
                                                        alt="Cover">
                                                @else
                                                    <img src="https://via.placeholder.com/45x65" alt="Cover">
                                                @endif
                                                <div>
                                                    <h4>{{ $item->buku->judul_buku }}</h4>
                                                    <p>{{ $item->buku->penulis }}</p>
                                                </div>
                                            </div>
                                            <div class="h-date">
                                                <i data-lucide="calendar"></i>
                                                {{-- Tampilkan tanggal dikembalikan (jika ada) atau tanggal update terakhir --}}
                                                {{ $item->tanggal_dikembalikan ? \Carbon\Carbon::parse($item->tanggal_dikembalikan)->format('d M Y') : \Carbon\Carbon::parse($item->updated_at)->format('d M Y') }}
                                            </div>
                                            <div class="h-status">
                                                @if ($item->status == 'dikembalikan')
                                                    <span class="badge-success"><i data-lucide="check"></i> Selesai</span>
                                                @elseif($item->status == 'batal')
                                                    <span class="badge-success"
                                                        style="background: #f1f5f9; color: #64748b; border-color: #e2e8f0;">
                                                        <i data-lucide="x"></i> Batal
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div style="text-align: center; padding: 30px; color: #94a3b8;">
                                            <p>Belum ada riwayat peminjaman.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>
                </main>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function openTab(tabId, btnElement) {
            const panes = document.querySelectorAll('.tab-pane');
            panes.forEach(pane => {
                pane.style.display = 'none';
                pane.classList.remove('active');
            });

            // 2. Tampilkan tab yang dipilih
            const selectedPane = document.getElementById(tabId);
            if (selectedPane) {
                selectedPane.style.display = 'block';
                // Tambahkan delay sedikit untuk animasi fadeIn
                setTimeout(() => selectedPane.classList.add('active'), 10);
            }

            // 3. Update status tombol
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            if (btnElement) {
                btnElement.classList.add('active');
            }
        }
    </script>
@endpush
