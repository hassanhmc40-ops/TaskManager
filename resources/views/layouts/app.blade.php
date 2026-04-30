<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'TaskManager') – TaskManager</title>
        <meta name="description" content="@yield('meta_description', 'Manage, filter, and track your personal productivity with TaskManager.')">

        <!-- Fonts: Inter from Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* ─── Design Token Overrides ─── */
            :root {
                --color-primary:       #4F46E5;
                --color-primary-dark:  #4338CA;
                --color-primary-light: #EEF2FF;
                --color-success:       #16A34A;
                --color-success-bg:    #DCFCE7;
                --color-danger:        #DC2626;
                --color-danger-bg:     #FEE2E2;
                --color-warning:       #D97706;
                --color-warning-bg:    #FEF3C7;
                --color-gray-50:       #F9FAFB;
                --color-gray-100:      #F3F4F6;
                --color-gray-200:      #E5E7EB;
                --color-gray-400:      #9CA3AF;
                --color-gray-500:      #6B7280;
                --color-gray-600:      #4B5563;
                --color-gray-700:      #374151;
                --color-gray-800:      #1F2937;
                --color-gray-900:      #111827;
            }

            *, *::before, *::after { box-sizing: border-box; }

            body {
                font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
                background-color: #F3F4F6;
                color: var(--color-gray-800);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            /* ─── Navbar ─── */
            .tm-navbar {
                background: #ffffff;
                border-bottom: 1px solid var(--color-gray-200);
                position: sticky;
                top: 0;
                z-index: 50;
            }
            .tm-navbar-inner {
                max-width: 1280px;
                margin: 0 auto;
                padding: 0 1.5rem;
                height: 60px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .tm-brand {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-weight: 700;
                font-size: 1.1rem;
                color: var(--color-gray-900);
                text-decoration: none;
            }
            .tm-brand-icon {
                width: 32px;
                height: 32px;
                background: var(--color-primary);
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .tm-brand-icon svg { color: #fff; }

            .tm-nav-right {
                display: flex;
                align-items: center;
                gap: 1rem;
            }
            .tm-bell-btn {
                background: none;
                border: none;
                cursor: pointer;
                color: var(--color-gray-500);
                padding: 0.4rem;
                border-radius: 8px;
                transition: background 0.15s;
            }
            .tm-bell-btn:hover { background: var(--color-gray-100); color: var(--color-gray-700); }

            /* User avatar + name dropdown */
            .tm-user-menu { position: relative; }
            .tm-user-trigger {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                cursor: pointer;
                padding: 0.25rem 0.5rem;
                border-radius: 8px;
                border: none;
                background: none;
                font-size: 0.875rem;
                font-weight: 500;
                color: var(--color-gray-700);
                transition: background 0.15s;
            }
            .tm-user-trigger:hover { background: var(--color-gray-100); }
            .tm-avatar {
                width: 34px;
                height: 34px;
                border-radius: 50%;
                background: var(--color-primary);
                color: #fff;
                font-weight: 600;
                font-size: 0.8rem;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                overflow: hidden;
            }
            .tm-avatar img { width: 100%; height: 100%; object-fit: cover; }

            .tm-dropdown {
                display: none;
                position: absolute;
                right: 0;
                top: calc(100% + 8px);
                background: #fff;
                border: 1px solid var(--color-gray-200);
                border-radius: 10px;
                box-shadow: 0 8px 24px rgba(0,0,0,0.10);
                min-width: 180px;
                z-index: 100;
                overflow: hidden;
            }
            .tm-dropdown.open { display: block; }
            .tm-dropdown a,
            .tm-dropdown button {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                width: 100%;
                padding: 0.65rem 1rem;
                font-size: 0.875rem;
                color: var(--color-gray-700);
                background: none;
                border: none;
                text-decoration: none;
                cursor: pointer;
                transition: background 0.12s;
                text-align: left;
            }
            .tm-dropdown a:hover,
            .tm-dropdown button:hover { background: var(--color-gray-50); color: var(--color-gray-900); }
            .tm-dropdown-divider { height: 1px; background: var(--color-gray-200); margin: 0.25rem 0; }

            /* ─── Flash Alerts ─── */
            .tm-flash {
                max-width: 1280px;
                margin: 1rem auto 0;
                padding: 0 1.5rem;
            }
            .tm-alert {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
                padding: 0.85rem 1.1rem;
                border-radius: 10px;
                font-size: 0.875rem;
                font-weight: 500;
                margin-bottom: 0.5rem;
            }
            .tm-alert-success {
                background: var(--color-success-bg);
                color: var(--color-success);
                border: 1px solid #bbf7d0;
            }
            .tm-alert-error {
                background: var(--color-danger-bg);
                color: var(--color-danger);
                border: 1px solid #fecaca;
            }
            .tm-alert-close {
                background: none;
                border: none;
                cursor: pointer;
                color: inherit;
                opacity: 0.7;
                font-size: 1rem;
                line-height: 1;
                padding: 0;
            }
            .tm-alert-close:hover { opacity: 1; }

            /* ─── Main Content Wrapper ─── */
            .tm-main {
                flex: 1;
                max-width: 1280px;
                margin: 0 auto;
                width: 100%;
                padding: 2rem 1.5rem;
            }

            /* ─── Footer ─── */
            .tm-footer {
                background: #fff;
                border-top: 1px solid var(--color-gray-200);
                padding: 1rem 1.5rem;
                margin-top: auto;
            }
            .tm-footer-inner {
                max-width: 1280px;
                margin: 0 auto;
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-size: 0.8rem;
                color: var(--color-gray-400);
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            .tm-footer-links { display: flex; gap: 1.25rem; }
            .tm-footer-links a {
                color: var(--color-gray-500);
                text-decoration: none;
                transition: color 0.15s;
            }
            .tm-footer-links a:hover { color: var(--color-gray-800); }

            /* ─── Utility: Btn Primary ─── */
            .btn-primary {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                background: var(--color-primary);
                color: #fff;
                font-weight: 600;
                font-size: 0.875rem;
                padding: 0.55rem 1.1rem;
                border-radius: 8px;
                border: none;
                cursor: pointer;
                text-decoration: none;
                transition: background 0.15s, transform 0.1s;
            }
            .btn-primary:hover { background: var(--color-primary-dark); transform: translateY(-1px); }
            .btn-primary:active { transform: translateY(0); }

            .btn-secondary {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                background: #fff;
                color: var(--color-gray-700);
                font-weight: 500;
                font-size: 0.875rem;
                padding: 0.55rem 1.1rem;
                border-radius: 8px;
                border: 1px solid var(--color-gray-200);
                cursor: pointer;
                text-decoration: none;
                transition: background 0.15s, border-color 0.15s;
            }
            .btn-secondary:hover { background: var(--color-gray-50); border-color: var(--color-gray-300); }

            .btn-danger {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                background: #fff;
                color: var(--color-danger);
                font-weight: 500;
                font-size: 0.875rem;
                padding: 0.55rem 1.1rem;
                border-radius: 8px;
                border: 1px solid #fecaca;
                cursor: pointer;
                text-decoration: none;
                transition: background 0.15s;
            }
            .btn-danger:hover { background: var(--color-danger-bg); }

            /* ─── Status Badges ─── */
            .badge {
                display: inline-flex;
                align-items: center;
                padding: 0.2rem 0.65rem;
                border-radius: 999px;
                font-size: 0.75rem;
                font-weight: 600;
                white-space: nowrap;
            }
            .badge-todo       { background: #F3F4F6; color: #374151; }
            .badge-inprogress { background: #EFF6FF; color: #1D4ED8; }
            .badge-completed  { background: #F0FDF4; color: #166534; }
            .badge-category   {
                background: var(--color-primary-light);
                color: var(--color-primary);
                border-radius: 999px;
                padding: 0.15rem 0.6rem;
                font-size: 0.7rem;
                font-weight: 600;
            }

            /* ─── Form Inputs ─── */
            .tm-input {
                width: 100%;
                padding: 0.6rem 0.85rem;
                border: 1px solid var(--color-gray-200);
                border-radius: 8px;
                font-size: 0.875rem;
                font-family: inherit;
                color: var(--color-gray-800);
                background: #fff;
                transition: border-color 0.15s, box-shadow 0.15s;
                outline: none;
            }
            .tm-input:focus {
                border-color: var(--color-primary);
                box-shadow: 0 0 0 3px rgba(79,70,229,0.12);
            }
            .tm-input.is-invalid { border-color: var(--color-danger); }

            .tm-label {
                display: block;
                font-size: 0.8rem;
                font-weight: 600;
                color: var(--color-gray-700);
                margin-bottom: 0.35rem;
            }
            .tm-error {
                font-size: 0.78rem;
                color: var(--color-danger);
                margin-top: 0.3rem;
            }
            .tm-hint {
                font-size: 0.78rem;
                color: var(--color-gray-400);
                margin-top: 0.25rem;
            }

            /* ─── Card ─── */
            .tm-card {
                background: #fff;
                border: 1px solid var(--color-gray-200);
                border-radius: 12px;
                overflow: hidden;
            }

            /* ─── Responsive ─── */
            @media (max-width: 640px) {
                .tm-navbar-inner { padding: 0 1rem; }
                .tm-main { padding: 1.25rem 1rem; }
                .tm-user-name { display: none; }
            }
        </style>
    </head>
    <body>

        {{-- ══════════════════════════════════════════════
             TOP NAVIGATION BAR
        ══════════════════════════════════════════════ --}}
        <nav class="tm-navbar" role="navigation" aria-label="Main navigation">
            <div class="tm-navbar-inner">

                {{-- Brand / Logo --}}
                <a href="{{ route('tasks.index') }}" class="tm-brand" id="nav-brand">
                    <span class="tm-brand-icon">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </span>
                    TaskManager
                </a>

                {{-- Right side controls --}}
                <div class="tm-nav-right">

                    {{-- Notification Bell --}}
                    <button class="tm-bell-btn" id="nav-bell-btn" aria-label="Notifications">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>

                    {{-- User dropdown --}}
                    @auth
                    <div class="tm-user-menu" id="user-menu">
                        <button class="tm-user-trigger" id="user-menu-btn"
                                aria-haspopup="true" aria-expanded="false"
                                onclick="toggleUserMenu()">
                            <div class="tm-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="tm-user-name">{{ Auth::user()->name }}</span>
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div class="tm-dropdown" id="user-dropdown" role="menu">
                            <a href="{{ route('profile.edit') }}" role="menuitem" id="nav-profile-link">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profile
                            </a>
                            <a href="{{ route('tasks.index') }}" role="menuitem" id="nav-tasks-link">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                                </svg>
                                My Tasks
                            </a>
                            <div class="tm-dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <button type="submit" role="menuitem" id="nav-logout-btn">
                                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                    @endauth

                    @guest
                    <a href="{{ route('login') }}" class="btn-primary" id="nav-login-btn">Sign In</a>
                    @endguest
                </div>

            </div>
        </nav>

        {{-- ══════════════════════════════════════════════
             FLASH MESSAGES
        ══════════════════════════════════════════════ --}}
        @if(session('success') || session('error'))
        <div class="tm-flash" role="region" aria-label="Notifications">
            @if(session('success'))
            <div class="tm-alert tm-alert-success" id="flash-success" role="alert">
                <span style="display:flex;align-items:center;gap:0.5rem;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </span>
                <button class="tm-alert-close" onclick="this.closest('.tm-alert').remove()" aria-label="Dismiss">✕</button>
            </div>
            @endif
            @if(session('error'))
            <div class="tm-alert tm-alert-error" id="flash-error" role="alert">
                <span style="display:flex;align-items:center;gap:0.5rem;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </span>
                <button class="tm-alert-close" onclick="this.closest('.tm-alert').remove()" aria-label="Dismiss">✕</button>
            </div>
            @endif
        </div>
        @endif

        {{-- ══════════════════════════════════════════════
             MAIN PAGE CONTENT
        ══════════════════════════════════════════════ --}}
        <main class="tm-main" id="main-content">
            @yield('content')
        </main>

        {{-- ══════════════════════════════════════════════
             FOOTER
        ══════════════════════════════════════════════ --}}
        <footer class="tm-footer" role="contentinfo">
            <div class="tm-footer-inner">
                <span>© {{ date('Y') }} TaskManager Inc. All rights reserved.</span>
                <nav class="tm-footer-links" aria-label="Footer links">
                    <a href="#" id="footer-privacy">Privacy Policy</a>
                    <a href="#" id="footer-terms">Terms of Service</a>
                    <a href="#" id="footer-support">Support</a>
                </nav>
            </div>
        </footer>

        {{-- ══════════════════════════════════════════════
             GLOBAL JAVASCRIPT
        ══════════════════════════════════════════════ --}}
        <script>
            // User dropdown toggle
            function toggleUserMenu() {
                const dropdown = document.getElementById('user-dropdown');
                const btn      = document.getElementById('user-menu-btn');
                const isOpen   = dropdown.classList.toggle('open');
                btn.setAttribute('aria-expanded', isOpen);
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function (e) {
                const menu = document.getElementById('user-menu');
                if (menu && !menu.contains(e.target)) {
                    document.getElementById('user-dropdown')?.classList.remove('open');
                    document.getElementById('user-menu-btn')?.setAttribute('aria-expanded', 'false');
                }
            });

            // Auto-dismiss flash messages after 5 s
            setTimeout(function () {
                document.getElementById('flash-success')?.remove();
                document.getElementById('flash-error')?.remove();
            }, 5000);
        </script>

        @yield('scripts')
    </body>
</html>
