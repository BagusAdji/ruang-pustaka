<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ruang Pustaka - SMKN 5 Surakarta')</title>

    <link rel="stylesheet" href="{{ asset('css/users/layout.css') }}">

    {{-- Stack untuk CSS khusus per halaman (misal: koleksi.css atau detail-buku.css) --}}
    @stack('styles')

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>

    <nav class="navbar">
        <div class="container navbar-container">
            <a href="{{ route('user.home') }}" class="logo">
                <div class="logo-icon">
                    <img src="{{ asset('/image/logo.png') }}" alt="">
                </div>
                <div class="logo-text">
                    <span class="brand-top">RUANG PUSTAKA</span>
                </div>
            </a>

            <div class="nav-links">
                <a href="{{ route('user.home') }}" class="nav-link {{ Request::is('/') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('user.koleksi') }}"
                    class="nav-link {{ Request::is('koleksi*') ? 'active' : '' }}">Koleksi Pustaka</a>
                {{-- <a href="#kontak" class="nav-link">Kontak</a> --}}
            </div>

            <form action="{{ route('user.koleksi') }}" method="GET" class="search-desktop">
                <button type="submit" class="search-btn-submit">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                </button>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari buku..."
                    class="search-input">
                @if (request('search'))
                    <a href="{{ route('user.koleksi') }}" class="search-reset-btn" title="Hapus pencarian">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </form>
            @auth
                {{-- JIKA SUDAH LOGIN --}}
                <a href="{{ route('user.profil') }}">
                    <div class="user-profile">
                        <div class="user-info">
                            <div class="user-name">{{ Auth::user()->nama }}</div>
                        </div>
                        <div class="user-avatar">
                            @if (Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="User Avatar"
                                    style="object-fit: cover;">
                            @else
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}"
                                    alt="User Avatar">
                            @endif
                        </div>
                    </div>
                </a>
            @endauth

            @guest
                {{-- JIKA BELUM LOGIN (Tamu) --}}
                <a href="{{ route('login') }}" class="btn-login">Masuk</a>
            @endguest

            <button id="mobile-menu-btn" class="mobile-menu-btn">
                <i data-lucide="menu"></i>
            </button>
        </div>


        <div id="mobile-menu" class="mobile-menu hidden">
            <a href="{{ route('user.home') }}" class="mobile-link">Beranda</a>
            <a href="{{ route('user.koleksi') }}" class="mobile-link">Koleksi Pustaka</a>
            {{-- <a href="#kontak" class="mobile-link">Kontak</a> --}}

            <hr style="margin: 10px 0; border: 0; border-top: 1px solid #eee;">

            @auth
                <div class="mobile-profile">
                    <div class="mobile-avatar">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}"
                            alt="User Avatar">
                    </div>
                    <div class="mobile-user-info">
                        <div class="user-name">{{ Auth::user()->name }}</div>

                        {{-- Form Logout Mobile --}}
                        <form action="{{ route('logout') }}" method="POST" style="margin-top:5px;">
                            @csrf
                            <button type="submit"
                                style="background:none; border:none; color:red; font-size:0.9rem; cursor:pointer;">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            @guest
                <div style="padding: 1rem;">
                    <a href="{{ route('login') }}" class="btn-login" style="display: block; text-align: center;">Masuk
                        Akun</a>
                </div>
            @endguest
        </div>
    </nav>

    @yield('content')


    <footer id="kontak" class="footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                <div class="footer-logo">
                    <div class="icon-box"><i data-lucide="book-open"></i></div>
                    <div class="text-box">
                        <h4>RUANG</h4>
                        <h4 class="highlight">PUSTAKA</h4>
                    </div>
                </div>
                <p>Sistem informasi manajemen perpustakaan digital SMK Negeri 5 Surakarta.</p>
                <div class="social-links">
                    <a href="#"><i data-lucide="instagram"></i></a>
                    <a href="#"><i data-lucide="facebook"></i></a>
                    <a href="#"><i data-lucide="youtube"></i></a>
                </div>
            </div>

            <div class="footer-links">
                <h4>Layanan Pengguna</h4>
                <ul>
                    <li><a href="#"><i data-lucide="chevron-right"></i> Panduan Peminjaman</a></li>
                    <li><a href="#"><i data-lucide="chevron-right"></i> Info Anggota</a></li>
                    <li><a href="#"><i data-lucide="chevron-right"></i> FAQ</a></li>
                </ul>
            </div>

            <div class="footer-links">
                <h4>Kategori Populer</h4>
                <ul>
                    <li><a href="#">Teknologi & Rekayasa</a></li>
                    <li><a href="#">Sastra & Novel</a></li>
                    <li><a href="#">Buku Paket</a></li>
                </ul>
            </div>

            <div class="footer-contact">
                <h4>Hubungi Kami</h4>
                <ul>
                    <li>
                        <i data-lucide="map-pin"></i>
                        <span>Jl. Adi Sucipto No.42, Surakarta</span>
                    </li>
                    <li>
                        <i data-lucide="phone"></i>
                        <span>(0271) 714901</span>
                    </li>
                    <li>
                        <i data-lucide="mail"></i>
                        <span>perpus@smkn5solo.sch.id</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container footer-bottom">
            <p>&copy; 2025 Ruang Pustaka SMK Negeri 5 Surakarta.</p>
            <div class="footer-legal">
                <a href="#">Syarat Ketentuan</a>
                <span>|</span>
                <a href="#">Kebijakan Privasi</a>
            </div>
        </div>
    </footer>

    <script>
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        lucide.createIcons();
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');

            if (btn && menu) {
                btn.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                });
            }
        });
    </script>
    {{-- Stack untuk script khusus per halaman --}}
    @stack('scripts')
</body>

</html>
