<div class="tm-card" style="padding:1.75rem;">

    {{-- Section header --}}
    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.5rem;
                padding-bottom:1.25rem;border-bottom:1px solid var(--color-gray-200);">
        <div style="width:38px;height:38px;background:var(--color-primary-light);border-radius:10px;
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"
                 stroke="var(--color-primary)" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div>
            <h2 style="font-size:1rem;font-weight:700;color:var(--color-gray-900);margin:0 0 0.15rem;">
                Profile Information
            </h2>
            <p style="font-size:0.8rem;color:var(--color-gray-500);margin:0;">
                Update your account name and email address.
            </p>
        </div>
    </div>

    {{-- Hidden verification form --}}
    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- Profile update form --}}
    <form method="POST" action="{{ route('profile.update') }}" id="profile-info-form">
        @csrf
        @method('PATCH')

        {{-- Name --}}
        <div style="margin-bottom:1.25rem;">
            <label for="name" class="tm-label">
                Full Name <span style="color:var(--color-danger);">*</span>
            </label>
            <div style="position:relative;">
                <span style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);
                             pointer-events:none;color:var(--color-gray-400);">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </span>
                <input type="text"
                       id="name"
                       name="name"
                       class="tm-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       value="{{ old('name', $user->name) }}"
                       required autofocus autocomplete="name"
                       style="padding-left:2.25rem;">
            </div>
            @error('name')
                <p class="tm-error" id="name-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div style="margin-bottom:1.5rem;">
            <label for="email" class="tm-label">
                Email Address <span style="color:var(--color-danger);">*</span>
            </label>
            <div style="position:relative;">
                <span style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);
                             pointer-events:none;color:var(--color-gray-400);">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </span>
                <input type="email"
                       id="email"
                       name="email"
                       class="tm-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       value="{{ old('email', $user->email) }}"
                       required autocomplete="username"
                       style="padding-left:2.25rem;">
            </div>
            @error('email')
                <p class="tm-error" id="email-error">{{ $message }}</p>
            @enderror

            {{-- Email verification notice --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="margin-top:0.75rem;padding:0.75rem 1rem;background:#FEF3C7;
                            border:1px solid #FDE68A;border-radius:8px;
                            display:flex;align-items:flex-start;gap:0.6rem;">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24"
                         stroke="#D97706" stroke-width="2" style="flex-shrink:0;margin-top:1px;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    <div>
                        <p style="font-size:0.8rem;color:#92400E;margin:0 0 0.3rem;font-weight:600;">
                            Email not verified
                        </p>
                        <button form="send-verification"
                                style="font-size:0.8rem;color:#D97706;background:none;border:none;
                                       cursor:pointer;padding:0;text-decoration:underline;font-weight:500;">
                            Click here to re-send the verification email.
                        </button>
                        @if (session('status') === 'verification-link-sent')
                            <p style="font-size:0.78rem;color:#166534;margin:0.3rem 0 0;font-weight:500;">
                                ✓ A new verification link has been sent to your email.
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Save button + success message --}}
        <div style="display:flex;align-items:center;gap:1rem;
                    border-top:1px solid var(--color-gray-200);padding-top:1.25rem;">
            <button type="submit" class="btn-primary" id="btn-save-profile">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <span id="profile-saved-msg"
                      style="font-size:0.85rem;color:var(--color-success);font-weight:600;
                             display:inline-flex;align-items:center;gap:0.35rem;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Profile saved successfully!
                </span>
                <script>
                    setTimeout(function() {
                        var el = document.getElementById('profile-saved-msg');
                        if (el) el.style.display = 'none';
                    }, 3000);
                </script>
            @endif
        </div>

    </form>
</div>
