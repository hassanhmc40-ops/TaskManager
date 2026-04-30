<div class="tm-card" style="padding:1.75rem;">

    {{-- Section header --}}
    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.5rem;
                padding-bottom:1.25rem;border-bottom:1px solid var(--color-gray-200);">
        <div style="width:38px;height:38px;background:#EFF6FF;border-radius:10px;
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"
                 stroke="#1D4ED8" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <div>
            <h2 style="font-size:1rem;font-weight:700;color:var(--color-gray-900);margin:0 0 0.15rem;">
                Update Password
            </h2>
            <p style="font-size:0.8rem;color:var(--color-gray-500);margin:0;">
                Use a long, random password to keep your account secure.
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('password.update') }}" id="update-password-form">
        @csrf
        @method('PUT')

        {{-- Current Password --}}
        <div style="margin-bottom:1.25rem;">
            <label for="update_password_current_password" class="tm-label">
                Current Password <span style="color:var(--color-danger);">*</span>
            </label>
            <div style="position:relative;">
                <span style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);
                             pointer-events:none;color:var(--color-gray-400);">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </span>
                <input type="password"
                       id="update_password_current_password"
                       name="current_password"
                       class="tm-input {{ $errors->updatePassword->has('current_password') ? 'is-invalid' : '' }}"
                       autocomplete="current-password"
                       style="padding-left:2.25rem;">
            </div>
            @if ($errors->updatePassword->has('current_password'))
                <p class="tm-error">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        {{-- New Password --}}
        <div style="margin-bottom:1.25rem;">
            <label for="update_password_password" class="tm-label">
                New Password <span style="color:var(--color-danger);">*</span>
            </label>
            <div style="position:relative;">
                <span style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);
                             pointer-events:none;color:var(--color-gray-400);">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                </span>
                <input type="password"
                       id="update_password_password"
                       name="password"
                       class="tm-input {{ $errors->updatePassword->has('password') ? 'is-invalid' : '' }}"
                       autocomplete="new-password"
                       style="padding-left:2.25rem;"
                       oninput="checkPasswordStrength(this.value)">
            </div>
            @if ($errors->updatePassword->has('password'))
                <p class="tm-error">{{ $errors->updatePassword->first('password') }}</p>
            @endif
            {{-- Password strength meter --}}
            <div style="margin-top:0.5rem;">
                <div style="height:4px;background:var(--color-gray-200);border-radius:99px;overflow:hidden;">
                    <div id="pw-strength-bar"
                         style="height:100%;width:0%;border-radius:99px;transition:width .3s,background .3s;
                                background:var(--color-danger);"></div>
                </div>
                <p id="pw-strength-label"
                   style="font-size:0.72rem;color:var(--color-gray-400);margin:0.25rem 0 0;"></p>
            </div>
        </div>

        {{-- Confirm Password --}}
        <div style="margin-bottom:1.75rem;">
            <label for="update_password_password_confirmation" class="tm-label">
                Confirm New Password <span style="color:var(--color-danger);">*</span>
            </label>
            <div style="position:relative;">
                <span style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);
                             pointer-events:none;color:var(--color-gray-400);">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </span>
                <input type="password"
                       id="update_password_password_confirmation"
                       name="password_confirmation"
                       class="tm-input {{ $errors->updatePassword->has('password_confirmation') ? 'is-invalid' : '' }}"
                       autocomplete="new-password"
                       style="padding-left:2.25rem;">
            </div>
            @if ($errors->updatePassword->has('password_confirmation'))
                <p class="tm-error">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        {{-- Actions --}}
        <div style="display:flex;align-items:center;gap:1rem;
                    border-top:1px solid var(--color-gray-200);padding-top:1.25rem;">
            <button type="submit" class="btn-primary" id="btn-save-password">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Update Password
            </button>

            @if (session('status') === 'password-updated')
                <span id="password-saved-msg"
                      style="font-size:0.85rem;color:var(--color-success);font-weight:600;
                             display:inline-flex;align-items:center;gap:0.35rem;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Password updated!
                </span>
                <script>
                    setTimeout(function() {
                        var el = document.getElementById('password-saved-msg');
                        if (el) el.style.display = 'none';
                    }, 3000);
                </script>
            @endif
        </div>
    </form>
</div>

<script>
function checkPasswordStrength(val) {
    var bar   = document.getElementById('pw-strength-bar');
    var label = document.getElementById('pw-strength-label');
    if (!bar) return;
    var score = 0;
    if (val.length >= 8)                        score++;
    if (/[A-Z]/.test(val))                      score++;
    if (/[0-9]/.test(val))                      score++;
    if (/[^A-Za-z0-9]/.test(val))              score++;
    var configs = [
        { w: '0%',   bg: 'var(--color-danger)',  txt: '' },
        { w: '25%',  bg: 'var(--color-danger)',  txt: 'Weak' },
        { w: '50%',  bg: 'var(--color-warning)', txt: 'Fair' },
        { w: '75%',  bg: '#10B981',              txt: 'Good' },
        { w: '100%', bg: 'var(--color-success)', txt: 'Strong' },
    ];
    var c = configs[val.length === 0 ? 0 : score];
    bar.style.width      = c.w;
    bar.style.background = c.bg;
    label.textContent    = c.txt;
    label.style.color    = c.bg;
}
</script>
