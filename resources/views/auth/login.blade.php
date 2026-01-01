<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Login</title>

    <link rel="stylesheet" href="/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
</head>

<body>

    <div class="main-content">

        <section class="login-section">
            <div class="login-wrapper">
                <div class="header-text">
                    <h1>Selamat Datang</h1>
                    <p>Masukkan Email dan Password</p>
                </div>
                @error('email')
                    <span style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">
                        {{ $message }}
                    </span>
                @enderror
                <form action="{{ Route('login.process') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <div class="input-container">
                            <input type="text" name="email" class="form-input" placeholder="user@email.com"
                                style="padding-right: 3rem;" value="{{ old('email') }}">
                            <span class="material-symbols-outlined input-icon-right">email</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-container">
                            <input type="password" id="passwordInput" name="password" class="form-input"
                                placeholder="Enter your password" style="padding-right: 3rem;">
                            <button type="button" class="btn-toggle-password" onclick="togglePassword()">
                                <span class="material-symbols-outlined" id="icon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <div class="form-actions">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-submit">Sign In</button>

                    <div class="footer-text">
                        <p>Belum Memiliki Akun? <a href="{{ route('register') }}">Daftar disini</a></p>
                    </div>
                </form>
            </div>
        </section>

        <section class="hero-section">
            <div class="hero-bg"></div>
            {{-- <div class="hero-overlay"></div> --}}
            {{-- <div class="hero-tint"></div> --}}

            <div class="hero-card">
                <div class="hero-badge">
                    <span class="material-symbols-outlined">schedule</span>
                    <span>Opening Hours</span>
                </div>
                <h3>Study late? We're open until 10 PM.</h3>
                <p>Access thousands of digital resources, reserve study rooms, and manage your loans all from your
                    account.</p>
            </div>
        </section>

    </div>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("passwordInput");
            const icon = document.getElementById("icon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.textContent = "visibility_off";
            } else {
                passwordInput.type = "password";
                icon.textContent = "visibility";
            }
        }
    </script>
</body>

</html>
