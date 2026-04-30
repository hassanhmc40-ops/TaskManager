@extends('layouts.app')

@section('title', 'My Profile')
@section('meta_description', 'Manage your TaskManager account settings and preferences.')

@section('content')

{{-- ══════════════════════════════════════════════
     PROFILE HERO CARD
══════════════════════════════════════════════ --}}
<div class="tm-card" style="margin-bottom:1.5rem;overflow:visible;">

    {{-- Gradient banner --}}
    <div style="height:100px;background:linear-gradient(135deg,#4F46E5 0%,#7C3AED 50%,#0EA5E9 100%);
                border-radius:12px 12px 0 0;position:relative;">
        {{-- decorative circles --}}
        <div style="position:absolute;top:-20px;right:-20px;width:160px;height:160px;
                    background:rgba(255,255,255,0.06);border-radius:50%;"></div>
        <div style="position:absolute;bottom:-30px;right:80px;width:90px;height:90px;
                    background:rgba(255,255,255,0.05);border-radius:50%;"></div>
    </div>

    {{-- Avatar + name row --}}
    <div style="padding:0 1.75rem 1.5rem;display:flex;align-items:flex-end;
                justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-top:-40px;">

        <div style="display:flex;align-items:flex-end;gap:1.1rem;flex-wrap:wrap;">
            {{-- Avatar circle --}}
            <div style="width:80px;height:80px;border-radius:50%;background:var(--color-primary);
                        color:#fff;font-size:2rem;font-weight:700;display:flex;
                        align-items:center;justify-content:center;
                        border:4px solid #fff;box-shadow:0 4px 16px rgba(79,70,229,.3);
                        flex-shrink:0;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div style="padding-bottom:0.25rem;">
                <h1 style="font-size:1.35rem;font-weight:700;color:var(--color-gray-900);
                           margin:0 0 0.2rem;">{{ $user->name }}</h1>
                <p style="font-size:0.85rem;color:var(--color-gray-500);margin:0;
                          display:flex;align-items:center;gap:0.4rem;">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ $user->email }}
                </p>
            </div>
        </div>

        {{-- Stats chips --}}
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;padding-bottom:0.25rem;">

            {{-- Email verified badge --}}
            @if($user->email_verified_at)
            <span style="display:inline-flex;align-items:center;gap:0.35rem;
                         background:#F0FDF4;color:#166534;border:1px solid #bbf7d0;
                         font-size:0.75rem;font-weight:600;padding:0.3rem 0.75rem;border-radius:999px;">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Verified
            </span>
            @else
            <span style="display:inline-flex;align-items:center;gap:0.35rem;
                         background:#FEF3C7;color:#92400E;border:1px solid #fde68a;
                         font-size:0.75rem;font-weight:600;padding:0.3rem 0.75rem;border-radius:999px;">
                Unverified
            </span>
            @endif

            {{-- Member since --}}
            <span style="display:inline-flex;align-items:center;gap:0.35rem;
                         background:var(--color-primary-light);color:var(--color-primary);
                         border:1px solid #c7d2fe;font-size:0.75rem;font-weight:600;
                         padding:0.3rem 0.75rem;border-radius:999px;">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                Member since {{ $user->created_at->format('M Y') }}
            </span>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     TAB NAV
══════════════════════════════════════════════ --}}
<div style="display:flex;gap:0.25rem;margin-bottom:1.25rem;border-bottom:2px solid var(--color-gray-200);
            padding-bottom:0;" id="profile-tabs">
    @foreach([
        ['tab' => 'info',     'label' => 'Profile Information', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ['tab' => 'password', 'label' => 'Password',            'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
        ['tab' => 'danger',   'label' => 'Delete Account',      'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'],
    ] as $t)
    <button onclick="switchTab('{{ $t['tab'] }}')"
            id="tab-btn-{{ $t['tab'] }}"
            style="display:inline-flex;align-items:center;gap:0.45rem;padding:0.65rem 1rem;
                   font-size:0.85rem;font-weight:600;border:none;background:none;cursor:pointer;
                   border-bottom:2px solid transparent;margin-bottom:-2px;transition:all .15s;
                   color:var(--color-gray-500);"
            aria-selected="false">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $t['icon'] }}"/>
        </svg>
        {{ $t['label'] }}
    </button>
    @endforeach
</div>

{{-- ══════════════════════════════════════════════
     TAB PANELS
══════════════════════════════════════════════ --}}
<div style="max-width:640px;">

    {{-- ── Profile Information ── --}}
    <div id="panel-info" class="profile-panel">
        @include('profile.partials.update-profile-information-form')
    </div>

    {{-- ── Password ── --}}
    <div id="panel-password" class="profile-panel" style="display:none;">
        @include('profile.partials.update-password-form')
    </div>

    {{-- ── Delete Account ── --}}
    <div id="panel-danger" class="profile-panel" style="display:none;">
        @include('profile.partials.delete-user-form')
    </div>

</div>

{{-- Delete confirmation modal (vanilla JS) --}}
<div id="delete-modal-overlay"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);
            z-index:200;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:14px;padding:2rem;max-width:420px;width:90%;
                box-shadow:0 20px 60px rgba(0,0,0,.2);" role="dialog" aria-modal="true"
         aria-labelledby="modal-title">
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
            <div style="width:40px;height:40px;background:var(--color-danger-bg);border-radius:50%;
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24"
                     stroke="var(--color-danger)" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <h2 id="modal-title"
                style="font-size:1rem;font-weight:700;color:var(--color-gray-900);margin:0;">
                Delete Account?
            </h2>
        </div>
        <p style="font-size:0.875rem;color:var(--color-gray-500);margin:0 0 1.25rem;line-height:1.6;">
            This will permanently delete your account and all associated tasks. This action <strong>cannot</strong> be undone.
        </p>

        <form method="POST" action="{{ route('profile.destroy') }}" id="confirm-delete-form">
            @csrf
            @method('DELETE')
            <div style="margin-bottom:1rem;">
                <label for="modal-password" class="tm-label">
                    Confirm your password
                </label>
                <input type="password" name="password" id="modal-password"
                       class="tm-input {{ $errors->userDeletion->has('password') ? 'is-invalid' : '' }}"
                       placeholder="Enter your password" required autocomplete="current-password">
                @if($errors->userDeletion->has('password'))
                    <p class="tm-error">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>
            <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn-secondary" onclick="closeDeleteModal()">
                    Cancel
                </button>
                <button type="submit" class="btn-danger"
                        style="background:var(--color-danger);color:#fff;">
                    Yes, Delete Account
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // ── Tab switching ──────────────────────────────────────
    var tabs    = ['info', 'password', 'danger'];
    var active  = '{{ $errors->userDeletion->isNotEmpty() ? "danger" : ($errors->updatePassword->isNotEmpty() ? "password" : "info") }}';

    function switchTab(name) {
        tabs.forEach(function(t) {
            var panel = document.getElementById('panel-' + t);
            var btn   = document.getElementById('tab-btn-' + t);
            var isActive = (t === name);
            panel.style.display = isActive ? 'block' : 'none';
            btn.style.color        = isActive ? 'var(--color-primary)' : 'var(--color-gray-500)';
            btn.style.borderBottomColor = isActive ? 'var(--color-primary)' : 'transparent';
            btn.setAttribute('aria-selected', isActive);
        });
    }

    // Set initial active tab
    switchTab(active);

    // ── Delete modal ──────────────────────────────────────
    function openDeleteModal() {
        var overlay = document.getElementById('delete-modal-overlay');
        overlay.style.display = 'flex';
        document.getElementById('modal-password').focus();
    }
    function closeDeleteModal() {
        document.getElementById('delete-modal-overlay').style.display = 'none';
    }
    // Close on overlay click
    document.getElementById('delete-modal-overlay').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
    // Open automatically if there are deletion errors
    @if($errors->userDeletion->isNotEmpty())
        openDeleteModal();
    @endif
</script>
@endsection
