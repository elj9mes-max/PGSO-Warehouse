<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse Inventory System - Login</title>
    
    {{-- Modern Sans Font for SaaS Aesthetic --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" crossorigin="anonymous">

    <style>
        /* CSS Reset & Variable Tokens */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg-page: #ffffff;
            --bg-card: #ffffff;
            --text-primary: #09090b;
            --text-secondary: #52525b;
            --text-muted: #71717a;
            --border-subtle: #e4e4e7;
            --border-focus: #09090b;
            --btn-bg: #09090b;
            --btn-hover: #27272a;
            --btn-text: #ffffff;
            --badge-bg: #f4f4f5;
            --badge-text: #18181b;
            --status-online: #10b981;
            --danger-bg: #fef2f2;
            --danger-border: #fecaca;
            --danger-text: #991b1b;
            --font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 32px 20px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Container Layout */
        .auth-container {
            width: 100%;
            max-width: 440px;
            margin: auto;
        }

        /* SaaS Card Structure */
        .auth-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 40px 36px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 
                        0 12px 24px -4px rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.2s ease;
        }

        /* Header / Brand Elements */
        .brand-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-icon-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            border-radius: 11px;
            background-color: #09090b;
            color: #ffffff;
            margin-bottom: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08);
        }

        .brand-icon-box svg {
            width: 22px;
            height: 22px;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--badge-text);
            background-color: var(--badge-bg);
            border: 1px solid var(--border-subtle);
            padding: 4px 10px;
            border-radius: 9999px;
            margin-bottom: 14px;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: var(--status-online);
        }

        .brand-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.025em;
            line-height: 1.25;
            margin-bottom: 6px;
        }

        .brand-subtitle {
            font-size: 0.875rem;
            color: var(--text-secondary);
            line-height: 1.45;
        }

        /* Form Layout */
        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
            text-align: left;
        }

        .form-label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: -0.01em;
        }

        .form-link {
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .form-link:hover {
            color: var(--text-primary);
            text-decoration: underline;
        }

        /* Input Controls */
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-prefix {
            position: absolute;
            left: 14px;
            color: #a1a1aa;
            display: flex;
            align-items: center;
            pointer-events: none;
        }

        .input-icon-prefix svg {
            width: 17px;
            height: 17px;
        }

        .form-input {
            width: 100%;
            height: 44px;
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            padding: 0 14px 0 40px;
            font-size: 0.9375rem;
            font-family: inherit;
            color: var(--text-primary);
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-input::placeholder {
            color: #a1a1aa;
        }

        .form-input:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.08);
        }

        .password-toggle-btn {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            padding: 6px;
            cursor: pointer;
            color: #71717a;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: color 0.15s ease, background-color 0.15s ease;
        }

        .password-toggle-btn:hover {
            color: var(--text-primary);
            background-color: #f4f4f5;
        }

        .password-toggle-btn svg {
            width: 18px;
            height: 18px;
        }

        /* Checkbox */
        .remember-wrapper {
            display: flex;
            align-items: center;
            gap: 9px;
            cursor: pointer;
            user-select: none;
        }

        .custom-checkbox {
            position: relative;
            width: 17px;
            height: 17px;
            border: 1.5px solid #d4d4d8;
            border-radius: 4px;
            background-color: #ffffff;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            outline: none;
            display: grid;
            place-content: center;
            transition: all 0.15s ease;
        }

        .custom-checkbox:checked {
            background-color: #09090b;
            border-color: #09090b;
        }

        .custom-checkbox:checked::after {
            content: "";
            width: 9px;
            height: 5px;
            border-left: 2px solid #ffffff;
            border-bottom: 2px solid #ffffff;
            transform: rotate(-45deg) translate(1px, -1px);
        }

        .custom-checkbox:focus-visible {
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.12);
        }

        .remember-label {
            font-size: 0.8438rem;
            color: var(--text-secondary);
            cursor: pointer;
        }

        /* Action Buttons */
        .btn-submit {
            width: 100%;
            height: 44px;
            background-color: var(--btn-bg);
            color: var(--btn-text);
            border: 1px solid var(--btn-bg);
            border-radius: 8px;
            font-size: 0.9375rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s ease;
            margin-top: 4px;
        }

        .btn-submit:hover {
            background-color: var(--btn-hover);
            border-color: var(--btn-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .btn-submit:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .btn-submit svg {
            width: 16px;
            height: 16px;
            transition: transform 0.15s ease;
        }

        .btn-submit:hover svg {
            transform: translateX(2px);
        }

        /* Switch Route Link */
        .switch-auth {
            text-align: center;
            font-size: 0.8438rem;
            color: var(--text-secondary);
            margin-top: 8px;
        }

        .switch-auth a {
            color: var(--text-primary);
            font-weight: 600;
            text-decoration: none;
            transition: underline 0.15s ease;
        }

        .switch-auth a:hover {
            text-decoration: underline;
        }

        /* Notice & Card Details */
        .card-security-note {
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-size: 0.7813rem;
            color: var(--text-muted);
            text-align: center;
        }

        .card-security-note svg {
            width: 14px;
            height: 14px;
            color: #71717a;
            flex-shrink: 0;
        }

        /* Page Footer */
        .page-footer {
            margin-top: 36px;
            text-align: center;
            font-size: 0.8125rem;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .footer-nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
        }

        .footer-nav a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.7813rem;
            transition: color 0.15s ease;
        }

        .footer-nav a:hover {
            color: var(--text-primary);
        }

        .footer-meta {
            font-size: 0.75rem;
            color: #a1a1aa;
        }

        /* Laravel Error Message Box */
        .alert-error {
            background-color: var(--danger-bg);
            border: 1px solid var(--danger-border);
            color: var(--danger-text);
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.8438rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 9px;
            text-align: left;
        }

        .alert-error svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        @media (max-width: 480px) {
            body {
                padding: 16px;
            }
            .auth-card {
                padding: 28px 20px;
                border-radius: 12px;
            }
            .brand-title {
                font-size: 1.35rem;
            }
        }
    </style>
</head>
<body>

    {{-- Main Container --}}
    <div class="auth-container">
        <div class="auth-card">
            
            {{-- Brand Header --}}
            <div class="brand-header">
                {{-- Warehouse Box SaaS Icon --}}
                <div class="brand-icon-box" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                        <path d="m3.3 7 8.7 5 8.7-5"/>
                        <path d="M12 22V12"/>
                    </svg>
                </div>

                <div>
                    <span class="badge-status">
                        <span class="status-dot"></span>
                        PGSO • Property & Supply
                    </span>
                </div>

                <h1 class="brand-title">Warehouse Inventory</h1>
                <p class="brand-subtitle">Enter your credentials to access the inventory management portal.</p>
            </div>

            {{-- Optional Laravel Flash / Error Notice --}}
            @if(session('status'))
                <div class="badge-status" style="width: 100%; justify-content: center; margin-bottom: 16px; padding: 10px; background-color: #f4fdf8; border-color: #bbf7d0; color: #166534;">
                    <span class="status-dot" style="background-color: #10b981;"></span>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div class="alert-error" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>
                        {{ session('error') ?? ($errors->first() ?? 'Invalid login credentials. Please try again.') }}
                    </span>
                </div>
            @endif

            {{-- Login Form --}}
            <form action="{{ route('login.post') }}" method="POST" class="auth-form" id="loginForm">
                @csrf

                {{-- Email or Employee ID --}}
                <div class="form-group">
                    <label for="email" class="form-label">Email or Employee ID</label>
                    <div class="input-wrapper">
                        <span class="input-icon-prefix">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="16" x="2" y="4" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            class="form-input" 
                            placeholder="admin@pgso.gov.ph or EMP-1029" 
                            required 
                            autofocus 
                            autocomplete="username"
                        >
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <div class="form-label-row">
                        <label for="password" class="form-label">Password</label>
                        <a href="#" class="form-link">Forgot password?</a>
                    </div>
                    <div class="input-wrapper">
                        <span class="input-icon-prefix">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="••••••••••••" 
                            required 
                            autocomplete="current-password"
                        >
                        <button type="button" class="password-toggle-btn" id="togglePasswordBtn" aria-label="Toggle password visibility">
                            <svg id="eyeOpenIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="eyeClosedIcon" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9.88 9.88 4.24 4.24"/>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                <line x1="2" x2="22" y1="2" y2="22"/>
                                <path d="M2 2l20 20"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="remember-wrapper">
                    <input type="checkbox" id="remember" name="remember" class="custom-checkbox">
                    <label for="remember" class="remember-label">Keep me signed in on this device</label>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="btn-submit">
                    <span>Sign in to Dashboard</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>

                {{-- Switch to Signup --}}
                <p class="switch-auth">
                    Don't have an account? 
                    <a href="{{ Route::has('signup') ? route('signup') : url('/signup') }}">Create account</a>
                </p>
            </form>

            {{-- Security Notice --}}
            <div class="card-security-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <span>Restricted portal for authorized PGSO personnel only</span>
            </div>

        </div>
    </div>

    {{-- Page Footer --}}
    <footer class="page-footer">
        <div class="footer-nav">
            <a href="#">Help & Support</a>
            <span>•</span>
            <a href="#">Inventory Policies</a>
            <span>•</span>
            <a href="#">IT Helpdesk</a>
        </div>
        <p class="footer-meta">
            Provincial General Services Office (PGSO) • Warehouse & Logistics Division
        </p>
    </footer>

    {{-- Interactive Script for Password Toggle --}}
    <script>
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const eyeOpenIcon = document.getElementById('eyeOpenIcon');
        const eyeClosedIcon = document.getElementById('eyeClosedIcon');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function() {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                
                if (isPassword) {
                    eyeOpenIcon.style.display = 'none';
                    eyeClosedIcon.style.display = 'block';
                    toggleBtn.setAttribute('aria-label', 'Hide password');
                } else {
                    eyeOpenIcon.style.display = 'block';
                    eyeClosedIcon.style.display = 'none';
                    toggleBtn.setAttribute('aria-label', 'Show password');
                }
            });
        }
    </script>
</body>
</html>