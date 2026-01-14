@extends('users.layout')

@section('title', 'Koleksi Buku - Ruang Pustaka')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/users/buku/koleksi.css') }}">
@endpush

@section('content')
    @php use Illuminate\Support\Str; @endphp

    <section class="hero-banner">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Jelajahi Dunia Lewat <br> Lembaran Buku</h1>
        </div>
    </section>

    <!-- layanan -->
    <section class="container quick-access-section">
        <div class="qa-header">
            <h2>Layanan Siswa</h2>
            <p>Akses cepat untuk kebutuhan belajar dan literasi sobat pustaka.</p>
        </div>

        <div class="qa-grid">
            <div class="qa-card">
                <div class="qa-icon">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <h3>Jam Layanan</h3>
                <p>Senin - Jumat: 07.00 - 15.00<br>Istirahat tetap buka.</p>
            </div>

            <div class="qa-card">
                <div class="qa-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h3>Cari Buku (OPAC)</h3>
                <p>Cek ketersediaan buku pelajaran dan novel di sini.</p>
            </div>

            <div class="qa-card">
                <div class="qa-icon">
                    <i class="fa-solid fa-tablet-screen-button"></i>
                </div>
                <h3>E-Perpus</h3>
                <p>baca Buku digital lewat HP atau tablet di mana saja.</p>
            </div>

            <div class="qa-card">
                <div class="qa-icon">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <h3>Aturan Peminjaman</h3>
                <p>Info denda, batas waktu, dan kartu anggota.</p>
            </div>
        </div>
    </section>

    <section id="koleksi" class="container" style="margin-bottom: 50px; margin-top: 100px;">

        <div class="collection-header">
            <h2>Koleksi Buku</h2>
        </div>

        {{-- FILTER TOMBOL --}}
        <div class="filter-container">
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">Semua</button>
                @foreach ($kategori as $kat)
                    <button class="filter-btn" data-filter="{{ Str::slug($kat->nama_kategori) }}">
                        {{ $kat->nama_kategori }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- GRID BUKU --}}
        <div class="book-grid-modern">

            @forelse ($buku as $item)
                @php
                    $slugKategori = optional($item->kategori)->nama_kategori
                        ? Str::slug($item->kategori->nama_kategori)
                        : 'uncategorized';

                    $isHabis = $item->jumlah <= 0;
                @endphp

                <a href="{{ route('user.buku.show', $item->id) }}"
                    class="link book-item {{ $isHabis ? 'out-of-stock' : '' }}"
                    data-category="{{ $slugKategori }}">

                    <div class="card-modern">
                        <div class="card-cover-modern">

                            @if ($isHabis)
                                <div class="badge-overlay">
                                    <span>Stok Habis</span>
                                </div>
                            @endif

                            @if ($item->cover)
                                <img src="{{ asset('storage/' . $item->cover) }}" alt="{{ $item->judul_buku }}">
                            @else
                                <img src="https://via.placeholder.com/150" alt="No Cover">
                            @endif
                        </div>

                        <div class="card-body-modern">
                            <h3>{{ $item->judul_buku }}</h3>
                            <p>{{ $item->penulis }}</p>

                        </div>
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <div class="empty-icon-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <h3>Pencarian Tidak Ditemukan</h3>
                    <p>Maaf, buku yang Anda cari belum tersedia di koleksi kami.</p>
                    <a href="{{ route('user.koleksi') }}" class="btn-reset-search">Reset Pencarian</a>
                </div>
            @endforelse

            <div id="empty-message" class="empty-state" style="display: none;">
                <div class="empty-icon-box">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <h3>Kategori Kosong</h3>
                <p>Belum ada buku dalam kategori ini.</p>
            </div>

        </div>

    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterButtons = document.querySelectorAll('.filter-btn');
            // GANTI SELECTOR: targetkan class 'book-item' (tag <a>)
            const bookItems = document.querySelectorAll('.book-item');
            const emptyMessage = document.getElementById('empty-message');

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');

                    const filterValue = button.getAttribute('data-filter');
                    let visibleCount = 0;

                    // Loop pada item pembungkus (<a>)
                    bookItems.forEach(item => {
                        const itemCategory = item.getAttribute('data-category');

                        if (filterValue === 'all' || itemCategory.includes(filterValue)) {
                            // Gunakan 'block' atau '' (default CSS) agar tidak merusak layout flex/grid bawaan
                            item.style.display = 'block';

                            // Animasi
                            item.style.opacity = '0';
                            setTimeout(() => item.style.opacity = '1', 50);

                            visibleCount++;
                        } else {
                            // Ini akan menyembunyikan seluruh slot grid
                            item.style.display = 'none';
                        }
                    });

                    if (visibleCount === 0) {
                        emptyMessage.style.display = 'flex';
                        emptyMessage.style.opacity = '0';
                        setTimeout(() => emptyMessage.style.opacity = '1', 50);
                    } else {
                        emptyMessage.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endpush
