<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Register</title>
    <link rel="stylesheet" href="/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
</head>
<body>
    <div class="main-content">
        <section class="login-section">
            <div class="login-wrapper">
                <div class="header-text">
                    <h1>Buat Akun Baru</h1>
                    <p>Lengkapi data diri untuk mendaftar</p>
                </div>

                <form action="{{ route('register.process') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <div class="input-container">
                            <input type="text" name="nama" class="form-input" placeholder="Nama Anda" value="{{ old('nama') }}" required>
                            <span class="material-symbols-outlined input-icon-right">person</span>
                        </div>
                        @error('nama') <span style="color:red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <div class="input-container">
                            <input type="email" name="email" class="form-input" placeholder="user@email.com" value="{{ old('email') }}" required>
                            <span class="material-symbols-outlined input-icon-right">email</span>
                        </div>
                        @error('email') <span style="color:red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-container">
                            <input type="password" name="password" class="form-input" placeholder="Minimal 8 karakter" required>
                            <span class="material-symbols-outlined input-icon-right">lock</span>
                        </div>
                        @error('password') <span style="color:red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <div class="input-container">
                            <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password" required>
                            <span class="material-symbols-outlined input-icon-right">lock_reset</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" style="margin-top: 1rem;">Daftar Sekarang</button>

                    <div class="footer-text">
                        <p>Sudah punya akun? <a href="{{ route('login') }}">Login disini</a></p>
                    </div>
                </form>
            </div>
        </section>

        <section class="hero-section">
            <div class="hero-bg"></div>
            <div class="hero-card">
                <div class="hero-badge">
                    <span class="material-symbols-outlined">library_add</span>
                    <span>Join Us</span>
                </div>
                <h3>Start your learning journey.</h3>
                <p>Create an account to access our digital library collection and manage your borrowings efficiently.</p>
            </div>
        </section>
    </div>
</body>
</html>
