@extends('layouts.app')

@section('title', 'Edit Task – ' . $task->title)
@section('meta_description', 'Modify the details of your existing task.')

@section('content')

{{-- ── Back link ── --}}
<div style="margin-bottom:1rem;">
    <a href="{{ route('tasks.index') }}"
       id="back-to-tasks"
       style="display:inline-flex;align-items:center;gap:0.35rem;
              font-size:0.85rem;color:var(--color-gray-500);text-decoration:none;
              transition:color .15s;"
       onmouseover="this.style.color='var(--color-gray-800)'"
       onmouseout="this.style.color='var(--color-gray-500)'">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to My Tasks
    </a>
</div>

{{-- ── Page title ── --}}
<h1 style="font-size:1.6rem;font-weight:700;color:var(--color-gray-900);margin:0 0 1.5rem;">
    Edit Task
</h1>

{{-- ══════════════════════════════════════════════
     MAIN FORM CARD
══════════════════════════════════════════════ --}}
<div style="max-width:680px;">

    <div class="tm-card" style="padding:1.75rem;">

        {{-- Card header with current status badge --}}
        <div style="display:flex;align-items:flex-start;justify-content:space-between;
                    flex-wrap:wrap;gap:0.75rem;margin-bottom:1.5rem;">
            <div>
                <h2 style="font-size:1rem;font-weight:700;color:var(--color-gray-900);margin:0 0 0.2rem;">
                    Task Details
                </h2>
                <p style="font-size:0.8rem;color:var(--color-gray-500);margin:0;">
                    Modify the details of your existing task.
                </p>
            </div>

            {{-- Current status badge (read-only indicator) --}}
            @php
                $badgeClass = [
                    'to_do'       => 'badge-todo',
                    'in_progress' => 'badge-inprogress',
                    'completed'   => 'badge-completed',
                ][$task->status] ?? 'badge-todo';

                $statusLabel = [
                    'to_do'       => 'To Do',
                    'in_progress' => 'In Progress',
                    'completed'   => 'Completed',
                ][$task->status] ?? ucfirst(str_replace('_', ' ', $task->status));
            @endphp

            <span class="badge {{ $badgeClass }}" id="current-status-badge"
                  style="font-size:0.78rem;">
                {{ $statusLabel }}
            </span>
        </div>

        <form method="POST"
              action="{{ route('tasks.update', $task) }}"
              id="edit-task-form"
              novalidate>
            @csrf
            @method('PATCH')

            {{-- ── Task Title ── --}}
            <div style="margin-bottom:1.25rem;">
                <label for="title" class="tm-label">
                    Task Title <span style="color:var(--color-danger);">*</span>
                </label>
                <input type="text"
                       name="title"
                       id="title"
                       class="tm-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
                       value="{{ old('title', $task->title) }}"
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
                          rows="5"
                          placeholder="Briefly describe what needs to be done...">{{ old('description', $task->description) }}</textarea>
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
                                    {{ old('category_id', $task->category_id) == $category->id ? 'selected' : '' }}>
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
                    <label for="status" class="tm-label">
                        Status <span style="color:var(--color-danger);">*</span>
                    </label>
                    <select name="status"
                            id="status"
                            class="tm-input {{ $errors->has('status') ? 'is-invalid' : '' }}"
                            required>
                        <option value="to_do"
                                {{ old('status', $task->status) === 'to_do' ? 'selected' : '' }}>
                            To Do
                        </option>
                        <option value="in_progress"
                                {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>
                            In Progress
                        </option>
                        <option value="completed"
                                {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>
                    </select>
                    @error('status')
                        <p class="tm-error" id="status-error">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- ── Due Date ── --}}
            <div style="margin-bottom:1.5rem;">
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
                           value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                           min="{{ date('Y-m-d') }}"
                           style="padding-left:2.25rem;">
                </div>
                @error('due_date')
                    <p class="tm-error" id="due-date-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Created / Updated meta ── --}}
            <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;
                        padding:0.75rem;background:var(--color-gray-50);border-radius:8px;
                        margin-bottom:1.5rem;font-size:0.78rem;color:var(--color-gray-400);">
                <span style="display:inline-flex;align-items:center;gap:0.35rem;">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Created {{ $task->created_at->format('M d, Y') }} · {{ $task->created_at->format('g:i A') }}
                </span>
                <span style="display:inline-flex;align-items:center;gap:0.35rem;">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581
                                 m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Last updated {{ $task->updated_at->diffForHumans() }}
                </span>
            </div>

            {{-- ── Form Actions ── --}}
            <div style="display:flex;align-items:center;justify-content:flex-end;gap:0.75rem;
                        border-top:1px solid var(--color-gray-200);padding-top:1.25rem;">
                <a href="{{ route('tasks.index') }}"
                   class="btn-secondary"
                   id="btn-cancel-edit">
                    Cancel
                </a>
                <button type="submit"
                        class="btn-primary"
                        id="btn-update-task">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Task
                </button>
            </div>

        </form>
    </div>

    {{-- ── Privacy note (matching Visily design) ── --}}
    <div style="margin-top:1.25rem;background:var(--color-primary-light);border-radius:10px;
                padding:1rem 1.1rem;display:flex;align-items:flex-start;gap:0.65rem;">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
             stroke="var(--color-primary)" stroke-width="2" style="flex-shrink:0;margin-top:1px;">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
        </svg>
        <div>
            <p style="font-size:0.8rem;font-weight:700;color:var(--color-primary);margin:0 0 0.2rem;">
                Privacy Note
            </p>
            <p style="font-size:0.78rem;color:#4338CA;margin:0;line-height:1.5;">
                Changes to task visibility or ownership may affect team collaboration settings.
                Ensure all updates align with your project workspace guidelines.
            </p>
        </div>
    </div>

</div>

@endsection
