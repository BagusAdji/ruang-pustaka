@extends('users.layout')

@section('title', $buku->judul_buku . ' - Ruang Pustaka')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/users/buku/detail.css') }}">
@endpush

@section('content')
    <div class="notification-container">
        @if (session('success'))
            <div class="notify-card success" id="notification-card">
                <div class="notify-icon">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="notify-content">
                    <h4>Berhasil!</h4>
                    <p>{{ session('success') }}</p>
                </div>
                <button class="close-notify" onclick="closeNotification()">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="notify-card error" id="notification-card">
                <div class="notify-icon">
                    <i class="fa-solid fa-xmark"></i>
                </div>
                <div class="notify-content">
                    <h4>Gagal!</h4>
                    <p>{{ session('error') }}</p>
                </div>
                <button class="close-notify" onclick="closeNotification()">&times;</button>
            </div>
        @endif
    </div>
    <div class="container-detail-buku main-wrapper">

        <div class="book-detail-section">
            {{-- Bagian Kiri: Cover --}}
            <div class="detail-left">
                <div class="book-cover-wrapper">
                    <div class="best-seller-badge">Best Seller</div>
                    @if ($buku->cover)
                        <img src="{{ asset('storage/' . $buku->cover) }}" alt="{{ $buku->judul_buku }}" class="main-cover">
                    @else
                        <img src="https://via.placeholder.com/300x450" alt="No Cover" class="main-cover">
                    @endif
                </div>
            </div>

            {{-- Bagian Kanan: Informasi --}}
            <div class="detail-right">
                <div class="detail-header">
                    <div class="header-text">
                        <p class="author-name">{{ $buku->penulis }}</p>
                        <h1 class="book-title">{{ $buku->judul_buku }}</h1>
                    </div>

                    {{-- TOMBOL PINJAM MEMICU MODAL --}}
                    @auth
                        <button class="btn-pinjam" onclick="openModal()">Pinjam</button>
                    @else
                        <a href="{{ route('login') }}" class="btn-pinjam"
                            style="text-decoration: none; text-align:center;">Masuk</a>
                    @endauth
                </div>

                <div class="detail-synopsis">
                    <h3>Sinopsis</h3>
                    {{-- Gunakan Str::limit jika di database belum ada kolom sinopsis, atau tampilkan dummy --}}
                    <p id="synopsis-text" class="clamped">
                        {{ $buku->sinopsis ?? 'Sinopsis belum tersedia untuk buku ini. Namun buku ini sangat direkomendasikan untuk dibaca karena memiliki nilai edukasi yang tinggi.' }}
                    </p>
                    <a href="javascript:void(0)" id="read-more-btn" class="read-more">Baca Selengkapnya <span
                            class="arrow">∨</span></a>
                </div>

                <div class="detail-specs">
                    <h3>Detail Buku</h3>
                    <div class="specs-grid">
                        <div class="spec-item">
                            <span class="label">Penerbit</span>
                            <span class="value">{{ $buku->penerbit }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="label">Tahun Terbit</span>
                            <span class="value">{{ $buku->tahun_terbit }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="label">Stok</span>
                            <span class="value">{{ $buku->jumlah }} Buku</span>
                        </div>
                        <div class="spec-item">
                            <span class="label">ISBN</span>
                            <span class="value">{{ $buku->isbn_formatted ?? '-' }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="label">Kategori</span>
                            <span class="value highlight">{{ $buku->kategori->nama_kategori ?? 'Umum' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="related-books-section">
            <div class="section-header">
                <h2>Buku Serupa</h2>
                <a href="{{ route('user.koleksi') }}" class="view-all">Lihat Semua >></a>
            </div>

            <div class="related-grid">
                @foreach ($bukuSerupa as $item)
                    <div class="book-card">
                        <div class="card-img">
                            <a href="{{ route('user.buku.show', $item->id) }}">
                                @if ($item->cover)
                                    <img src="{{ asset('storage/' . $item->cover) }}" alt="{{ $item->judul_buku }}">
                                @else
                                    <img src="https://via.placeholder.com/150" alt="No Cover">
                                @endif
                            </a>
                        </div>
                        <div class="card-info">
                            <h4>{{ Str::limit($item->judul_buku, 20) }}</h4>
                            <p>{{ $item->penulis }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @auth
        <div id="modalPeminjaman" class="modal-overlay hidden">
            <div class="modal-modern-content">

                <div class="modal-modern-header">
                    <div>
                        <h3 class="modal-title">Konfirmasi Peminjaman</h3>
                        <p class="modal-subtitle">Pastikan detail buku dan durasi peminjaman sudah sesuai.</p>
                    </div>
                    <span class="close-btn-modern" onclick="closeModal()">&times;</span>
                </div>

                <form action="{{ route('user.peminjaman.store') }}" method="POST" class="modal-modern-body">
                    @csrf
                    <input type="hidden" name="id_buku" value="{{ $buku->id }}">

                    <div class="modal-col-left">
                        <div class="modal-book-cover">
                            @if ($buku->cover)
                                <img src="{{ asset('storage/' . $buku->cover) }}" alt="{{ $buku->judul_buku }}">
                            @else
                                <div class="placeholder-cover"></div>
                            @endif
                        </div>

                        <div class="modal-book-info">
                            <h4>{{ $buku->judul_buku }}</h4>
                            <p>{{ $buku->penulis }}</p>
                        </div>

                        <div class="modal-info-box">
                            <div class="info-row">
                                <span class="label">Kategori</span>
                                <span class="value">{{ $buku->kategori->nama_kategori ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Stok</span>
                                @if ($buku->jumlah > 1)
                                    <span class="value stock-ok"><i class="fa-solid fa-circle-check"></i> Tersedia</span>
                                @elseif($buku->jumlah < 1)
                                    <span class="value stock-no"><i class="fa-solid fa-ban"></i>Kosong</span>
                                @endif

                            </div>
                        </div>
                    </div>

                    <div class="modal-col-right">

                        <div class="form-group-modern">
                            <label>PEMINJAM</label>
                            <div class="user-card">
                                <div class="user-card-avatar">
                                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}"
                                        alt="Avatar">
                                </div>
                                <div class="user-card-info">
                                    <span class="name">{{ Auth::user()->name }}</span>
                                    <span class="id">Member ID:
                                        LIB-2025-{{ str_pad(Auth::id(), 3, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label>DURASI PEMINJAMAN</label>
                            <select name="durasi" id="durasi_pinjam" class="input-modern" onchange="updateReturnDate()">
                                <option value="3">3 Hari</option>
                                <option value="5">5 Hari</option>
                                <option value="7">7 Hari</option>
                            </select>
                        </div>

                        <div class="dates-grid">
                            <div class="date-box">
                                <span class="date-label"><i class="fa-regular fa-calendar"></i> Tanggal Pinjam</span>
                                <span class="date-value">{{ date('d M') }}</span>
                                <input type="hidden" name="tanggal_pinjam" value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="date-box active">
                                <span class="date-label"><i class="fa-solid fa-clock"></i> Tanggal Kembali</span>
                                <span class="date-value" id="tgl_kembali_display">-</span>
                            </div>
                        </div>

                        <div class="modal-actions">
                            <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                            <button type="submit" class="btn-confirm">Konfirmasi</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    @endauth

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const notification = document.getElementById('notification-card');
            if (notification) {
                // Hilang otomatis setelah 5 detik (5000ms)
                setTimeout(() => {
                    closeNotification();
                }, 3000);
            }
        });

        function closeNotification() {
            const notification = document.getElementById('notification-card');
            if (notification) {
                // Tambahkan animasi keluar
                notification.style.animation = 'slideOut 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55) forwards';

                // Hapus elemen dari DOM setelah animasi selesai
                setTimeout(() => {
                    notification.remove();
                }, 500);
            }
        }
        // --- LOGIKA TANGGAL OTOMATIS ---
        function updateReturnDate() {
            const durasiInput = document.getElementById('durasi_pinjam');
            const displayTanggal = document.getElementById('tgl_kembali_display');

            if (durasiInput && displayTanggal) {
                const duration = parseInt(durasiInput.value);
                const today = new Date();
                const returnDate = new Date(today);
                returnDate.setDate(today.getDate() + duration);

                const options = {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                };
                displayTanggal.textContent = returnDate.toLocaleDateString('id-ID', options);
            }
        }

        // --- LOGIKA MODAL (DIPERBAIKI) ---
        function openModal() {
            // Ambil elemen modal SAAT tombol diklik, bukan saat halaman load
            const modal = document.getElementById('modalPeminjaman');

            if (modal) {
                modal.classList.remove('hidden');
                // Sedikit delay agar transisi CSS opacity berjalan
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);

                // Update tanggal sekalian saat buka modal
                updateReturnDate();
            } else {
                console.error('Modal tidak ditemukan! Pastikan Anda sudah Login.');
            }
        }

        function closeModal() {
            const modal = document.getElementById('modalPeminjaman');
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300); // Sesuaikan dengan durasi transisi CSS
            }
        }

        // Event Listener saat halaman siap
        document.addEventListener('DOMContentLoaded', () => {
            // Update tanggal default
            updateReturnDate();

            // Tutup modal jika klik di area gelap (Overlay)
            const modal = document.getElementById('modalPeminjaman');
            if (modal) {
                window.onclick = function(event) {
                    if (event.target == modal) {
                        closeModal();
                    }
                }
            }
        });
        const readMoreBtn = document.getElementById('read-more-btn');
        const synopsisText = document.getElementById('synopsis-text');
        const arrowIcon = readMoreBtn.querySelector('.arrow');

        readMoreBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah layar scroll ke atas saat diklik

            // Cek apakah teks sedang terpotong (ada class 'clamped')
            if (synopsisText.classList.contains('clamped')) {
                // JIKA TERTUTUP -> BUKA
                synopsisText.classList.remove('clamped'); // Hapus pembatas baris
                readMoreBtn.childNodes[0].nodeValue = "Sembunyikan "; // Ubah teks tombol
                readMoreBtn.classList.add('active'); // Putar panah
            } else {
                // JIKA TERBUKA -> TUTUP
                synopsisText.classList.add('clamped'); // Pasang lagi pembatas baris
                readMoreBtn.childNodes[0].nodeValue = "Baca Selengkapnya "; // Balikkan teks tombol
                readMoreBtn.classList.remove('active'); // Balikkan panah
            }
        });
    </script>
@endpush
