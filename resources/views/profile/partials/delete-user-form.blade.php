<div class="tm-card" style="padding:1.75rem;border-color:#FECACA;">

    {{-- Section header --}}
    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.5rem;
                padding-bottom:1.25rem;border-bottom:1px solid #FECACA;">
        <div style="width:38px;height:38px;background:var(--color-danger-bg);border-radius:10px;
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"
                 stroke="var(--color-danger)" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <div>
            <h2 style="font-size:1rem;font-weight:700;color:var(--color-danger);margin:0 0 0.15rem;">
                Delete Account
            </h2>
            <p style="font-size:0.8rem;color:var(--color-gray-500);margin:0;">
                Permanently remove your account and all associated data.
            </p>
        </div>
    </div>

    {{-- Warning notice --}}
    <div style="background:var(--color-danger-bg);border:1px solid #FECACA;border-radius:10px;
                padding:1rem 1.1rem;margin-bottom:1.5rem;
                display:flex;align-items:flex-start;gap:0.65rem;">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
             stroke="var(--color-danger)" stroke-width="2" style="flex-shrink:0;margin-top:1px;">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <p style="font-size:0.82rem;color:var(--color-danger);margin:0;line-height:1.6;">
            Once your account is deleted, <strong>all of its resources and data will be permanently removed</strong>.
            Please download any data you wish to retain before proceeding.
        </p>
    </div>

    {{-- What will be deleted checklist --}}
    <div style="margin-bottom:1.75rem;">
        <p style="font-size:0.8rem;font-weight:600;color:var(--color-gray-600);margin:0 0 0.6rem;">
            The following will be permanently deleted:
        </p>
        @foreach(['Your profile and account credentials', 'All tasks you have created', 'Your category associations', 'All activity history'] as $item)
        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.4rem;">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24"
                 stroke="var(--color-danger)" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span style="font-size:0.82rem;color:var(--color-gray-600);">{{ $item }}</span>
        </div>
        @endforeach
    </div>

    {{-- Delete trigger button --}}
    <button type="button"
            id="btn-open-delete-modal"
            onclick="openDeleteModal()"
            style="display:inline-flex;align-items:center;gap:0.5rem;
                   background:var(--color-danger);color:#fff;font-weight:600;
                   font-size:0.875rem;padding:0.6rem 1.25rem;border-radius:8px;
                   border:none;cursor:pointer;transition:background .15s,transform .1s;"
            onmouseover="this.style.background='#B91C1C'"
            onmouseout="this.style.background='var(--color-danger)'">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Delete My Account
    </button>

</div>
