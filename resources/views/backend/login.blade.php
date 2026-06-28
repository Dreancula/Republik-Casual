<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Akses Internal - Republik Casual</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts for High-End Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --font-main: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --transition-smooth: cubic-bezier(0.25, 0.8, 0.25, 1);
            
            /* REPUBLIK CASUAL CORE LUXURY DARK THEME VARIABLES */
            --rc-accent: #EAE6DF;
            --rc-accent-rgb: 234, 230, 223;
            --bg-main: #060606;
            --bg-card: rgba(18, 18, 18, 0.35);
            --border-glass: rgba(255, 255, 255, 0.07);
            --text-main: #ffffff;
            --text-sub: #a1a1aa;
            --input-bg: rgba(255, 255, 255, 0.02);
            --input-focus-bg: rgba(255, 255, 255, 0.05);
            --shadow-card: rgba(0, 0, 0, 0.6);
            --btn-bg: var(--rc-accent);
            --btn-text: #000000;
            --btn-hover-bg: #ffffff;
        }

        body {
            margin: 0;
            padding: 0;
            background: var(--bg-main);
            color: var(--text-main);
            font-family: var(--font-main);
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* LIVE ANIMATED CANVAS BACKGROUND */
        #rcDynamicCanvas {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            z-index: 1;
            background: #060606;
            pointer-events: none;
        }

        /* VIGNETTE OVERLAY FOR DEPTH */
        .bg-vignette {
            position: fixed;
            inset: 0;
            z-index: 2;
            background: radial-gradient(circle, rgba(0,0,0,0) 20%, rgba(6,6,6,0.85) 90%);
            pointer-events: none;
        }

        /* Main Container */
        .rc-auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 2rem 1rem;
            z-index: 5;
        }

        .rc-side-form {
            width: 100%;
            max-width: 460px;
            z-index: 10;
        }

        /* Ultra Premium Glassmorphic Card */
        .rc-login-card {
            background: var(--bg-card);
            backdrop-filter: blur(30px) saturate(200%);
            -webkit-backdrop-filter: blur(30px) saturate(200%);
            border: 1px solid var(--border-glass);
            border-radius: 28px;
            padding: 3.5rem 2.5rem;
            box-shadow: 0 40px 80px -20px var(--shadow-card);
        }

        @media (max-width: 576px) {
            .rc-login-card { padding: 2.5rem 1.5rem; border-radius: 24px; }
        }

        /* Premium Input Styling */
        .rc-input-wrapper {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .rc-input-field {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            padding: 1rem 1.2rem 1rem 3.2rem;
            color: var(--text-main);
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s var(--transition-smooth);
        }

        .rc-input-field:focus {
            border-color: rgba(var(--rc-accent-rgb), 0.5);
            background: var(--input-focus-bg);
            box-shadow: 0 0 0 4px rgba(var(--rc-accent-rgb), 0.1);
            outline: none;
        }

        .rc-input-field-pass {
            padding-right: 3rem;
        }

        .rc-icon-left {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-sub);
            display: flex;
            align-items: center;
            pointer-events: none;
            transition: color 0.25s ease;
        }

        .rc-input-field:focus ~ .rc-icon-left {
            color: var(--text-main);
        }

        .rc-btn-toggle-pass {
            position: absolute;
            right: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-sub);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            z-index: 5;
        }

        /* Brand Identity */
        .brand-tagline {
            font-weight: 800; 
            font-size: 0.95rem; 
            letter-spacing: 3px; 
            text-transform: uppercase; 
            color: var(--text-main);
        }

        /* Submit Button (Luxury Brand Micro-interactions) */
        .rc-btn-submit {
            background: var(--btn-bg);
            color: var(--btn-text);
            border: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 1rem;
            letter-spacing: 0.5px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.2);
            transition: all 0.4s var(--transition-smooth);
        }

        .rc-btn-submit:hover {
            background: var(--btn-hover-bg);
            color: var(--btn-text);
            transform: translateY(-2px);
            box-shadow: 0 15px 32px rgba(0, 0, 0, 0.3);
        }

        .rc-btn-submit:active {
            transform: translateY(1px);
        }
    </style>
</head>
<body>

    <!-- ANIMATED LIVE CANVAS BACKGROUND -->
    <canvas id="rcDynamicCanvas"></canvas>
    <div class="bg-vignette"></div>

    <!-- MAIN CENTER CONTAINER -->
    <div class="rc-auth-container">
        
        <!-- CENTERED PREMIUM FORM -->
        <div class="rc-side-form">
            <div class="rc-login-card">
                
                <!-- BRAND IDENTITY ARCHITECTURE -->
                <div class="d-flex align-items-center gap-3 mb-5">
                    <img src="{{ asset('image/icon_rc.png') }}" 
                         alt="Republik Casual Logo" 
                         style="width: 48px; height: 48px; border-radius: 12px; object-fit: contain; background: rgba(255,255,255,0.05); padding: 4px; border: 1px solid var(--border-glass);">
                    <div style="line-height: 1.3;">
                        <div class="brand-tagline">
                            Republik Casual
                        </div>
                        <div style="font-size: 0.72rem; color: var(--text-sub); font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                            Internal Control Center
                        </div>
                    </div>
                </div>

                <!-- TITLE HEADLINES -->
                <div class="mb-4">
                    <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.75px; margin-bottom: 0.5rem;">
                        Selamat Datang
                    </h1>
                    <p style="color: var(--text-sub); font-size: 0.9rem; margin: 0; line-height: 1.6; font-weight: 500;">
                        Silakan otorisasi akun kredensial Anda untuk masuk ke sistem manajemen internal.
                    </p>
                </div>

                <!-- SYSTEM BACKEND MESSAGES HANDLER -->
                @if($errors->any())
                    <div class="p-3 mb-4 d-flex align-items-center gap-2" style="background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.15); border-radius: 14px; color: #ef4444; font-size: 0.85rem; font-weight: 600;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- CORE AUTH INTERFACE FORM -->
                <form method="POST" action="{{ route('admin.login.post') }}">
                    @csrf

                    <!-- EMAIL INPUT MODULE -->
                    <div class="mb-3">
                        <label class="form-label" style="color: var(--text-main); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Alamat Email</label>
                        <div class="rc-input-wrapper">
                            <input type="email" name="email" class="rc-input-field" placeholder="staff@republikcasual.com" value="{{ old('email') }}" required autofocus autocomplete="email">
                            <div class="rc-icon-left">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- PASSWORD INPUT MODULE WITH VISIBILITY TOGGLE -->
                    <div class="mb-4">
                        <label class="form-label" style="color: var(--text-main); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Kata Sandi</label>
                        <div class="rc-input-wrapper">
                            <input type="password" id="passwordField" name="password" class="rc-input-field rc-input-field-pass" placeholder="••••••••••••" required autocomplete="current-password">
                            <div class="rc-icon-left">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </div>
                            <button type="button" class="rc-btn-toggle-pass" onclick="togglePasswordVisibility()" aria-label="Lihat kata sandi">
                                <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- REMEMBER SESSION FLAG COUPLING -->
                    <div class="mb-4 d-flex align-items-center">
                        <label class="d-flex align-items-center gap-2" style="color: var(--text-sub); font-size: 0.85rem; cursor: pointer; user-select: none; font-weight: 500;">
                            <input type="checkbox" name="remember" style="accent-color: var(--rc-accent); width: 16px; height: 16px; border-radius: 4px; border: 1px solid var(--border-glass);"> Ingat sesi perangkat ini
                        </label>
                    </div>

                    <!-- ACTION TRIGGER BUTTON -->
                    <button type="submit" class="btn rc-btn-submit w-100">
                        Otorisasi & Masuk
                    </button>
                </form>

            </div>
        </div>

    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AUTOMATED CANVAS GRADIENT MESH SCRIPT -->
    <script>
        const canvas = document.getElementById('rcDynamicCanvas');
        const ctx = canvas.getContext('2d');

        let width = canvas.width = window.innerWidth;
        let height = canvas.height = window.innerHeight;

        window.addEventListener('resize', () => {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        });

        // Soft elegant mesh nodes tuned to Sand Accent palette
        class GradientNode {
            constructor() {
                this.x = Math.random() * width;
                this.y = Math.random() * height;
                this.radius = Math.random() * 250 + 200; // Large ambient glow
                // Extremely subtle custom color flow matching #EAE6DF
                this.color = `rgba(234, 230, 223, ${Math.random() * 0.03 + 0.015})`; 
                this.vx = (Math.random() - 0.5) * 0.4;
                this.vy = (Math.random() - 0.5) * 0.4;
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;

                if (this.x < -this.radius) this.x = width + this.radius;
                if (this.x > width + this.radius) this.x = -this.radius;
                if (this.y < -this.radius) this.y = height + this.radius;
                if (this.y > height + this.radius) this.y = -this.radius;
            }

            draw() {
                let gradient = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, this.radius);
                gradient.addColorStop(0, this.color);
                gradient.addColorStop(1, 'rgba(6, 6, 6, 0)');
                
                ctx.beginPath();
                ctx.fillStyle = gradient;
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        const nodes = [];
        for (let i = 0; i < 6; i++) {
            nodes.push(new GradientNode());
        }

        function animate() {
            ctx.clearRect(0, 0, width, height);
            ctx.fillStyle = '#060606';
            ctx.fillRect(0, 0, width, height);
            
            nodes.forEach(node => {
                node.update();
                node.draw();
            });
            requestAnimationFrame(animate);
        }
        animate();

        // Password Reveal Logic
        function togglePasswordVisibility() {
            const passField = document.getElementById('passwordField');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passField.type === 'password') {
                passField.type = 'text';
                eyeIcon.innerHTML = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>`;
            } else {
                passField.type = 'password';
                eyeIcon.innerHTML = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
            }
        }
    </script>
</body>
</html>