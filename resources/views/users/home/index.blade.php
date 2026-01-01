@extends('users.layout')

@section('title', 'Ruang Pustaka')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/users/home.css') }}">
@endpush

@section('content')
    <section class="hero-section">
        <div class="container hero-container">
            <div class="hero-content">
                <div class="hero-text">
                    <div class="badge">Perpustakaan SMK N 5 Surakarta</div>
                    <h1>
                        Selamat Datang di Portal <br>
                        <span class="highlight">Perpustakaan Digital</span> <br>
                        <span class="sub-heading">Ruang Pustaka</span>
                    </h1>
                    <p class="hero-desc">
                        Jelajahi ribuan koleksi buku digital dan fisik untuk menunjang pembelajaran. Membaca kini lebih
                        mudah, kapan saja dan di mana saja.
                    </p>
                    <div class="hero-actions">
                        <a href="{{ route('user.koleksi') }}" class="btn btn-primary">
                            Mulai Sekarang
                            <i data-lucide="arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="hero-image">
                <img src="{{ asset('/image/hero_book.png') }}">
            </div>
        </div>

        <!-- Wave Shape (SVG) -->
        <div class="wave-decoration">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                    fill="#3B82F6" opacity="0.2"></path>
                <path
                    d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                    fill="#3B82F6" opacity="0.5"></path>
                <path
                    d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                    fill="#EFF6FF"></path>
            </svg>
        </div>
    </section>

    <!-- TENTANG KAMI SECTION -->
    <section id="tentang" class="section bg-light-blue">
        <div class="container about-container">
            <div class="content-text">
                <h2 class="section-title">Tentang Kami</h2>
                <div class="text-block">
                    <p>
                        <strong>Perpustakaan Digital SMK N 5 Surakarta</strong> adalah sebuah platform perpustakaan
                        digital yang dirancang untuk memfasilitasi kebutuhan literasi seluruh warga sekolah. Di era
                        digital ini, kami hadir untuk mendekatkan buku kepada siswa dan guru melalui akses yang mudah,
                        cepat, dan fleksibel.
                    </p>
                    <p>
                        Tidak perlu lagi khawatir kehabisan waktu berkunjung ke perpustakaan fisik. Kami berkomitmen
                        untuk mendukung gerakan literasi sekolah dengan memanfaatkan teknologi informasi terkini,
                        menciptakan ekosistem belajar yang tanpa batas.
                    </p>
                </div>
            </div>
            <div class="content-image right-image">
                <div class="image-wrapper rotate-right">
                    <img src="{{ asset('/image/about.jpg') }}">
                </div>
            </div>
        </div>
    </section>

    <!-- VISI & MISI SECTION -->
    <section class="section bg-white">
        <div class="container visi-misi-container">
            <div class="content-image left-image">
                <div class="image-wrapper rotate-left">
                    <img src="{{ asset('/image/visimisi.jpg') }}">
                </div>
            </div>
            <div class="content-text">
                <h2 class="section-title">Visi & Misi</h2>

                <div class="vision-box">
                    <h3><i data-lucide="target"></i> Visi</h3>
                    <p>Menjadi pusat literasi berbasis teknologi yang unggul, informatif, dan mudah diakses untuk
                        menciptakan generasi SMK yang cerdas dan berwawasan luas.</p>
                </div>

                <div class="mission-box">
                    <h3><i data-lucide="list-checks"></i> Misi</h3>
                    <ul class="mission-list">
                        <li>
                            <span class="number">1</span>
                            <span>Menyediakan koleksi buku digital (e-book) yang relevan dengan kurikulum dan minat
                                siswa.</span>
                        </li>
                        <li>
                            <span class="number">2</span>
                            <span>Memberikan layanan peminjaman buku yang praktis dan efisien.</span>
                        </li>
                        <li>
                            <span class="number">3</span>
                            <span>Meningkatkan minat baca warga sekolah melalui kemudahan akses teknologi.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- STATISTIK SECTION (UPDATED FOR ANIMATION) -->
    <section class="section stats-section">
        <div class="stats-bg-pattern"></div>
        <div class="container stats-container">
            <div class="stat-item">
                <!-- Added 'counter' class and 'data-target' -->
                <div class="stat-number counter" data-target="{{ $total_buku }}">0</div>
                <div class="stat-label">Koleksi Buku</div>
            </div>
            <div class="stat-item">
                <div class="stat-number counter" data-target="1200">0</div>
                <div class="stat-label">E-Book Digital</div>
            </div>
            <div class="stat-item">
                <div class="stat-number counter" data-target="1500">0</div>
                <div class="stat-label">Anggota Aktif</div>
            </div>
            <div class="stat-item">
                <div class="stat-number counter" data-target="50">0</div>
                <div class="stat-label">Kunjungan Harian</div>
            </div>
        </div>
    </section>

    <!-- LAYANAN SECTION -->
    <section id="layanan" class="section bg-white">
        <div class="container">
            <div class="section-header">
                <span class="sub-header">Fasilitas Kami</span>
                <h2>Layanan Utama</h2>
                <div class="line-decorator"></div>
            </div>

            <div class="services-grid">
                <!-- Card 1 -->
                <div class="service-card light-card">
                    <div class="card-icon">
                        <i data-lucide="calendar-check"></i>
                    </div>
                    <h3>Sirkulasi Mandiri</h3>
                    <p>Layanan peminjaman dan pengembalian buku secara mandiri dengan sistem yang terintegrasi.</p>
                </div>

                <!-- Card 2 -->
                <div class="service-card primary-card">
                    <div class="card-decor"></div>
                    <div class="card-icon glass-icon">
                        <i data-lucide="book-open-check"></i>
                    </div>
                    <h3>Digital Library</h3>
                    <p>Akses ribuan e-book pelajaran dan novel populer langsung dari gadget Anda. Baca di mana saja.</p>
                    <a href="{{ route('user.koleksi') }}" class="btn btn-white full-width">Mulai Membaca</a>
                </div>

                <!-- Card 3 -->
                <div class="service-card light-card">
                    <div class="card-icon">
                        <i data-lucide="clock"></i>
                    </div>
                    <h3>Jam Layanan</h3>
                    <p>
                        Senin - Kamis: 07.00 - 15.30 WIB<br>
                        Jumat: 07.00 - 11.30 WIB<br>
                        Sabtu & Minggu: Libur
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- KOLEKSI SECTION -->
    <section id="koleksi" class="section bg-slate">
        <div class="container">
            <div class="collection-header">
                <div class="header-left">
                    <h2>Koleksi Terbaru</h2>
                    <p>Buku-buku yang baru saja mendarat di rak kami.</p>
                </div>
                <a href="{{ route('user.koleksi') }}" class="view-all-link">
                    Lihat Semua <i data-lucide="arrow-right"></i>
                </a>
            </div>

            <div class="books-grid">
                @forelse ($buku as $index => $item)
                    <a href="{{ route('user.buku.show', $item->id) }}" class="link book-item">

                        {{-- Hapus data-category dari div ini --}}
                        <div class="card-modern">
                            <div class="card-cover-modern">
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
                    <div class="col-span-full text-center">Tidak ada data buku saat ini.</div>
                @endforelse
            </div>

            <div class="mobile-view-all">
                <a href="{{ route('user.koleksi') }}" class="view-all-link">
                    Lihat Semua <i data-lucide="arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');

            if (btn && menu) {
                btn.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                });
            }

            // --- ANIMASI COUNTER STATISTIK ---
            const counters = document.querySelectorAll('.counter');
            const speed = 60; // Semakin kecil angka ini, semakin lambat animasinya

            // Fungsi untuk menjalankan animasi
            const animateCounters = () => {
                counters.forEach(counter => {
                    const updateCount = () => {
                        const target = +counter.getAttribute('data-target'); // Ambil angka tujuan
                        const count = +counter.innerText.replace('+',
                            ''); // Ambil angka saat ini (hapus tanda + jika ada)

                        // Hitung langkah penambahan (semakin besar target, langkah semakin besar)
                        const increment = target / speed;

                        if (count < target) {
                            // Jika belum sampai target, tambahkan angka
                            counter.innerText = Math.ceil(count + increment) + "+";
                            // Panggil fungsi ini lagi setelah 20ms
                            setTimeout(updateCount, 20);
                        } else {
                            // Jika sudah sampai, set angka ke target pasti
                            counter.innerText = target + "+";
                        }
                    };
                    updateCount();
                });
            };

            // Menggunakan Intersection Observer untuk mendeteksi kapan user scroll ke bagian statistik
            const observerOptions = {
                root: null,
                threshold: 0.4 // Animasi berjalan ketika 40% elemen terlihat di layar
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Jalankan animasi
                        animateCounters();
                        // Matikan observer agar animasi hanya berjalan sekali
                        observer.disconnect();
                    }
                });
            }, observerOptions);

            // Mulai observasi pada section statistik
            const statsSection = document.querySelector('.stats-section');
            if (statsSection) {
                observer.observe(statsSection);
            }
        });
    </script>
@endpush
