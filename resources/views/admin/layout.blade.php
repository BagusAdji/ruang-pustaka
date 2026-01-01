<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Admin Panel') - Ruang Pustaka</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />

    <link rel="stylesheet" href="/css/admin/layout.css">

    @stack('css')
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('/image/logo.png') }}" alt="Logo" class="logo-img">
            <div class="brand-text">
                <h1>Ruang Pustaka</h1>
                <p>Admin Dashboard</p>
            </div>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-label">Menu Utama</div>

            <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="material-symbols-outlined">grid_view</span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.buku.index') }}" class="sidebar-item {{ request()->routeIs('admin.buku.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">library_books</span>
                <span>Koleksi Buku</span>
            </a>

            <a href="{{ route('admin.kategori.index') }}" class="sidebar-item {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">category</span>
                <span>Kategori</span>
            </a>

            <a href="{{ route('admin.peminjaman.index') }}" class="sidebar-item {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">assignment_return</span>
                <span>Riwayat Peminjaman</span> </a>
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <span class="material-symbols-outlined">logout</span>
                    <span>Keluar Aplikasi</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="main-wrapper">

        <header class="topbar">
            <div class="page-info">
                <h2 class="page-title">@yield('page-title', 'Dashboard')</h2>
                <div class="breadcrumbs">Admin &rsaquo; @yield('page-title', 'Home')</div>
            </div>

            <div class="user-area">
                <div class="user-info">
                    <span class="user-name">{{ auth()->user()->nama }}</span>
                    <span class="user-role">Administrator</span>
                </div>
                <div class="user-avatar">
                    {{ substr(auth()->user()->nama, 0, 1) }}
                </div>
            </div>
        </header>

        @if (session('success'))
            <div id="toast-success" class="toast-success">
                <span class="material-symbols-outlined">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <main class="content-area">
            @yield('content')
        </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let toast = document.getElementById('toast-success');
            if(toast) {
                setTimeout(() => { toast.classList.add('show'); }, 100);
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 500);
                }, 4000);
            }
        });
    </script>
</body>
</html>
