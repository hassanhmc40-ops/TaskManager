@extends('layouts.app')

@section('title', 'Create New Task')
@section('meta_description', 'Add a new task to your personal workspace to stay organized.')

@section('content')

{{-- ── Back link ── --}}
<div style="margin-bottom:1.25rem;">
    <a href="{{ route('tasks.index') }}"
       id="back-to-dashboard"
       style="display:inline-flex;align-items:center;gap:0.35rem;
              font-size:0.85rem;color:var(--color-gray-500);text-decoration:none;
              transition:color .15s;"
       onmouseover="this.style.color='var(--color-gray-800)'"
       onmouseout="this.style.color='var(--color-gray-500)'">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Dashboard
    </a>
</div>

{{-- ── Page title ── --}}
<div style="margin-bottom:1.75rem;">
    <h1 style="font-size:1.6rem;font-weight:700;color:var(--color-gray-900);margin:0 0 0.3rem;">
        Create New Task
    </h1>
    <p style="font-size:0.875rem;color:var(--color-gray-500);margin:0;">
        Add a new task to your personal workspace to stay organized.
    </p>
</div>

{{-- ══════════════════════════════════════════════
     MAIN FORM CARD
══════════════════════════════════════════════ --}}
<div style="max-width:680px;">

    <div class="tm-card" style="padding:1.75rem;">

        <div style="margin-bottom:1.5rem;">
            <h2 style="font-size:1rem;font-weight:700;color:var(--color-gray-900);margin:0 0 0.2rem;">
                Task Details
            </h2>
            <p style="font-size:0.8rem;color:var(--color-gray-500);margin:0;">
                Enter the essential information for your upcoming task.
            </p>
        </div>

        <form method="POST"
              action="{{ route('tasks.store') }}"
              id="create-task-form"
              novalidate>
            @csrf

            {{-- ── Task Title ── --}}
            <div style="margin-bottom:1.25rem;">
                <label for="title" class="tm-label">
                    Task Title <span style="color:var(--color-danger);">*</span>
                </label>
                <input type="text"
                       name="title"
                       id="title"
                       class="tm-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
                       value="{{ old('title') }}"
                       placeholder="e.g., Q4 Performance Review"
                       maxlength="255"
                       required
                       autocomplete="off">
                @error('title')
                    <p class="tm-error" id="title-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Description ── --}}
            <div style="margin-bottom:1.25rem;">
                <label for="description" class="tm-label">Description</label>
                <textarea name="description"
                          id="description"
                          class="tm-input {{ $errors->has('description') ? 'is-invalid' : '' }}"
                          rows="4"
                          placeholder="Briefly describe what needs to be done...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="tm-error" id="description-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Category + Status (side by side) ── --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">

                {{-- Category --}}
                <div>
                    <label for="category_id" class="tm-label">
                        Category <span style="color:var(--color-danger);">*</span>
                    </label>
                    <select name="category_id"
                            id="category_id"
                            class="tm-input {{ $errors->has('category_id') ? 'is-invalid' : '' }}"
                            required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="tm-error" id="category-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="tm-label">Status</label>
                    <select name="status"
                            id="status"
                            class="tm-input {{ $errors->has('status') ? 'is-invalid' : '' }}"
                            required>
                        <option value="to_do"
                                {{ old('status', 'to_do') === 'to_do' ? 'selected' : '' }}>
                            To Do
                        </option>
                        <option value="in_progress"
                                {{ old('status') === 'in_progress' ? 'selected' : '' }}>
                            In Progress
                        </option>
                        <option value="completed"
                                {{ old('status') === 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>
                    </select>
                    @error('status')
                        <p class="tm-error" id="status-error">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- ── Due Date ── --}}
            <div style="margin-bottom:2rem;">
                <label for="due_date" class="tm-label">Due Date</label>
                <div style="position:relative;">
                    <span style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);
                                 pointer-events:none;color:var(--color-gray-400);">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8"  y1="2" x2="8"  y2="6"/>
                            <line x1="3"  y1="10" x2="21" y2="10"/>
                        </svg>
                    </span>
                    <input type="date"
                           name="due_date"
                           id="due_date"
                           class="tm-input {{ $errors->has('due_date') ? 'is-invalid' : '' }}"
                           value="{{ old('due_date') }}"
                           min="{{ date('Y-m-d') }}"
                           style="padding-left:2.25rem;">
                </div>
                @error('due_date')
                    <p class="tm-error" id="due-date-error">{{ $message }}</p>
                @enderror
                <p class="tm-hint">Optional. Must be today or a future date.</p>
            </div>

            {{-- ── Form Actions ── --}}
            <div style="display:flex;align-items:center;justify-content:flex-end;gap:0.75rem;
                        border-top:1px solid var(--color-gray-200);padding-top:1.25rem;">
                <a href="{{ route('tasks.index') }}"
                   class="btn-secondary"
                   id="btn-cancel-create">
                    Cancel
                </a>
                <button type="submit"
                        class="btn-primary"
                        id="btn-create-task">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Create Task
                </button>
            </div>

        </form>
    </div>

    {{-- ══════════════════════════════════════════════
         TIP CARDS (below the form, matching design)
    ══════════════════════════════════════════════ --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1.25rem;">

        <div style="background:var(--color-primary-light);border-radius:10px;padding:1rem 1.1rem;">
            <p style="font-size:0.8rem;font-weight:700;color:var(--color-primary);margin:0 0 0.3rem;
                      display:flex;align-items:center;gap:0.4rem;">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
                </svg>
                Quick Tip
            </p>
            <p style="font-size:0.78rem;color:#4338CA;margin:0;line-height:1.5;">
                Set a clear due date to receive reminder notifications 24 hours before the task is due.
            </p>
        </div>

        <div style="background:#F0FDF4;border-radius:10px;padding:1rem 1.1rem;">
            <p style="font-size:0.8rem;font-weight:700;color:var(--color-success);margin:0 0 0.3rem;
                      display:flex;align-items:center;gap:0.4rem;">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                             M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857
                             m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Collaborate
            </p>
            <p style="font-size:0.78rem;color:var(--color-success);margin:0;line-height:1.5;">
                Once created, you can share this task with your team members by clicking the share icon.
            </p>
        </div>

    </div>
</div>

@endsection
