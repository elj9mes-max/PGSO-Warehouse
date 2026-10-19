<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse Inventory System - Sign Up</title>
    
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
            max-width: 480px;
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
            margin-bottom: 28px;
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
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
            text-align: left;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: -0.01em;
        }

        /* Responsive Name Row */
        .names-row {
            display: grid;
            grid-template-columns: 1fr 78px 1fr;
            gap: 12px;
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

        .form-input.no-prefix {
            padding-left: 14px;
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

        .input-hint {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 2px;
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
            margin-top: 6px;
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

        @media (max-width: 520px) {
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
            .names-row {
                grid-template-columns: 1fr 1fr;
            }
            .name-field-mi {
                grid-column: span 2;
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
                        PGSO • Staff Registration
                    </span>
                </div>

                <h1 class="brand-title">Create Account</h1>
                <p class="brand-subtitle">Register your personnel profile to access warehouse inventory records.</p>
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
                        {{ session('error') ?? ($errors->first() ?? 'Please correct the errors in the form.') }}
                    </span>
                </div>
            @endif

            {{-- Signup Form --}}
            <form action="{{ route('signup.post') }}" method="POST" class="auth-form" id="signupForm">
                @csrf

                {{-- Name Fields Row: Firstname, Middle Initial, Lastname --}}
                <div class="names-row">
                    {{-- Firstname --}}
                    <div class="form-group">
                        <label for="first_name" class="form-label">First Name</label>
                        <div class="input-wrapper">
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                value="{{ old('first_name') }}"
                                class="form-input no-prefix" 
                                placeholder="Juan" 
                                required 
                                autofocus 
                                autocomplete="given-name"
                            >
                        </div>
                    </div>

                    {{-- Middle Initial --}}
                    <div class="form-group name-field-mi">
                        <label for="middle_initial" class="form-label">M.I.</label>
                        <div class="input-wrapper">
                            <input 
                                type="text" 
                                id="middle_initial" 
                                name="middle_initial" 
                                value="{{ old('middle_initial') }}"
                                class="form-input no-prefix" 
                                placeholder="A." 
                                maxlength="4"
                                autocomplete="additional-name"
                            >
                        </div>
                    </div>

                    {{-- Lastname --}}
                    <div class="form-group">
                        <label for="last_name" class="form-label">Last Name</label>
                        <div class="input-wrapper">
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                value="{{ old('last_name') }}"
                                class="form-input no-prefix" 
                                placeholder="Dela Cruz" 
                                required 
                                autocomplete="family-name"
                            >
                        </div>
                    </div>
                </div>

                {{-- Username --}}
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-wrapper">
                        <span class="input-icon-prefix">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            value="{{ old('username') }}"
                            class="form-input" 
                            placeholder="jdelacruz" 
                            required 
                            autocomplete="username"
                        >
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
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
                            autocomplete="new-password"
                        >
                        <button type="button" class="password-toggle-btn" id="togglePasswordBtn" aria-label="Toggle password visibility">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg class="eye-closed" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9.88 9.88 4.24 4.24"/>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                <line x1="2" x2="22" y1="2" y2="22"/>
                                <path d="M2 2l20 20"/>
                            </svg>
                        </button>
                    </div>
                    <span class="input-hint">Must be at least 8 characters.</span>
                </div>

                {{-- Confirm Password --}}
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon-prefix">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 12 2 2 4-4"/>
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="form-input" 
                            placeholder="••••••••••••" 
                            required 
                            autocomplete="new-password"
                        >
                        <button type="button" class="password-toggle-btn" id="toggleConfirmPasswordBtn" aria-label="Toggle confirm password visibility">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg class="eye-closed" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9.88 9.88 4.24 4.24"/>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                <line x1="2" x2="22" y1="2" y2="22"/>
                                <path d="M2 2l20 20"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="btn-submit">
                    <span>Create Staff Account</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>

                {{-- Switch to Login --}}
                <p class="switch-auth">
                    Already have an account? 
                    <a href="{{ Route::has('login') ? route('login') : url('/') }}">Sign in</a>
                </p>
            </form>

            {{-- Security Notice --}}
            <div class="card-security-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <span>New accounts are subject to PGSO Administrator verification</span>
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

    {{-- Interactive Script for Password Toggles --}}
    <script>
        function setupPasswordToggle(btnId, inputId) {
            const btn = document.getElementById(btnId);
            const input = document.getElementById(inputId);
            if (!btn || !input) return;

            const eyeOpen = btn.querySelector('.eye-open');
            const eyeClosed = btn.querySelector('.eye-closed');

            btn.addEventListener('click', function() {
                const isPassword = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPassword ? 'text' : 'password');
                
                if (isPassword) {
                    if (eyeOpen) eyeOpen.style.display = 'none';
                    if (eyeClosed) eyeClosed.style.display = 'block';
                    btn.setAttribute('aria-label', 'Hide password');
                } else {
                    if (eyeOpen) eyeOpen.style.display = 'block';
                    if (eyeClosed) eyeClosed.style.display = 'none';
                    btn.setAttribute('aria-label', 'Show password');
                }
            });
        }

        setupPasswordToggle('togglePasswordBtn', 'password');
        setupPasswordToggle('toggleConfirmPasswordBtn', 'password_confirmation');
    </script>
</body>
</html>
