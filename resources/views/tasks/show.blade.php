@extends('layouts.app')

@section('title', $task->title . ' – Task Detail')
@section('meta_description', 'View full details for task: ' . $task->title)

@section('content')

{{-- ══════════════════════════════════════════════
     BREADCRUMB
══════════════════════════════════════════════ --}}
<nav aria-label="Breadcrumb" style="margin-bottom:1.25rem;">
    <ol style="display:flex;align-items:center;gap:0.4rem;list-style:none;padding:0;margin:0;
               font-size:0.82rem;color:var(--color-gray-400);">
        <li>
            <a href="{{ route('tasks.index') }}" id="breadcrumb-tasks"
               style="color:var(--color-gray-500);text-decoration:none;transition:color .15s;"
               onmouseover="this.style.color='var(--color-gray-800)'"
               onmouseout="this.style.color='var(--color-gray-500)'">
                Tasks
            </a>
        </li>
        <li aria-hidden="true">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </li>
        @if($task->category)
        <li>
            <a href="{{ route('tasks.index', ['category' => $task->category_id]) }}"
               id="breadcrumb-category"
               style="color:var(--color-gray-500);text-decoration:none;transition:color .15s;"
               onmouseover="this.style.color='var(--color-gray-800)'"
               onmouseout="this.style.color='var(--color-gray-500)'">
                {{ $task->category->name }}
            </a>
        </li>
        <li aria-hidden="true">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </li>
        @endif
        <li aria-current="page" style="color:var(--color-gray-600);font-weight:500;
            max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
            {{ $task->title }}
        </li>
    </ol>
</nav>

{{-- ── Task ID + created + Edit link (top bar, like Visily show design) ── --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;
            gap:0.75rem;margin-bottom:0.5rem;">
    <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
        <span style="font-size:0.78rem;font-weight:600;color:var(--color-gray-400);
                     letter-spacing:.05em;text-transform:uppercase;">
            TASK-{{ str_pad($task->id, 3, '0', STR_PAD_LEFT) }}
        </span>
        <span style="font-size:0.78rem;color:var(--color-gray-400);">
            Created {{ $task->created_at->format('M d, Y') }}
        </span>
    </div>

    <a href="{{ route('tasks.edit', $task) }}"
       id="btn-edit-task"
       style="display:inline-flex;align-items:center;gap:0.4rem;
              font-size:0.85rem;font-weight:600;color:var(--color-gray-600);
              text-decoration:none;border:1px solid var(--color-gray-200);
              padding:0.4rem 0.9rem;border-radius:8px;background:#fff;
              transition:all .15s;"
       onmouseover="this.style.borderColor='var(--color-primary)';this.style.color='var(--color-primary)'"
       onmouseout="this.style.borderColor='var(--color-gray-200)';this.style.color='var(--color-gray-600)'">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                     M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>
        Edit
    </a>
</div>

{{-- ── Task Title ── --}}
<h1 style="font-size:1.65rem;font-weight:700;color:var(--color-gray-900);
           margin:0 0 1.25rem;line-height:1.3;">
    {{ $task->title }}
</h1>

{{-- ══════════════════════════════════════════════
     META ROW  (Category | Status | Due Date | Assigned)
══════════════════════════════════════════════ --}}
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

    $isOverdue = $task->due_date
        && $task->due_date->isPast()
        && $task->status !== 'completed';
@endphp

<div style="display:flex;flex-wrap:wrap;gap:1.5rem;margin-bottom:1.75rem;
            padding-bottom:1.25rem;border-bottom:1px solid var(--color-gray-200);">

    {{-- Category --}}
    <div style="display:flex;flex-direction:column;gap:0.35rem;">
        <span style="font-size:0.7rem;font-weight:700;text-transform:uppercase;
                     letter-spacing:.07em;color:var(--color-gray-400);">
            <svg width="11" height="11" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M7 7h.01M7 3h5l8 8-7 7-8-8V3z"/>
            </svg>
            Category
        </span>
        @if($task->category)
            <span class="badge badge-category" id="show-category">{{ $task->category->name }}</span>
        @else
            <span style="font-size:0.85rem;color:var(--color-gray-400);">Uncategorised</span>
        @endif
    </div>

    {{-- Status --}}
    <div style="display:flex;flex-direction:column;gap:0.35rem;">
        <span style="font-size:0.7rem;font-weight:700;text-transform:uppercase;
                     letter-spacing:.07em;color:var(--color-gray-400);">
            <svg width="11" height="11" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;">
                <circle cx="12" cy="12" r="10"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
            </svg>
            Status
        </span>
        <span class="badge {{ $badgeClass }}" id="show-status">{{ $statusLabel }}</span>
    </div>

    {{-- Due Date --}}
    <div style="display:flex;flex-direction:column;gap:0.35rem;">
        <span style="font-size:0.7rem;font-weight:700;text-transform:uppercase;
                     letter-spacing:.07em;color:var(--color-gray-400);">
            <svg width="11" height="11" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            Due Date
        </span>
        @if($task->due_date)
            <span id="show-due-date"
                  style="font-size:0.875rem;font-weight:600;
                         color:{{ $isOverdue ? 'var(--color-danger)' : 'var(--color-gray-700)' }};">
                {{ $task->due_date->format('M d, Y') }}
                @if($isOverdue)
                    <span style="background:var(--color-danger);color:#fff;
                                 font-size:0.65rem;font-weight:700;padding:0.1rem 0.4rem;
                                 border-radius:4px;margin-left:0.3rem;">OVERDUE</span>
                @endif
            </span>
        @else
            <span style="font-size:0.85rem;color:var(--color-gray-400);" id="show-due-date">No due date</span>
        @endif
    </div>

    {{-- Assigned to --}}
    <div style="display:flex;flex-direction:column;gap:0.35rem;">
        <span style="font-size:0.7rem;font-weight:700;text-transform:uppercase;
                     letter-spacing:.07em;color:var(--color-gray-400);">
            <svg width="11" height="11" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Assigned
        </span>
        <span style="display:inline-flex;align-items:center;gap:0.4rem;" id="show-assigned">
            <span class="tm-avatar" style="width:22px;height:22px;font-size:0.65rem;">
                {{ strtoupper(substr($task->user->name, 0, 1)) }}
            </span>
            <span style="font-size:0.875rem;font-weight:500;color:var(--color-gray-700);">
                {{ $task->user->name }}
            </span>
        </span>
    </div>

</div>

{{-- ══════════════════════════════════════════════
     TWO-COLUMN LAYOUT (Content + Sidebar)
══════════════════════════════════════════════ --}}
<div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start;">

    {{-- ── LEFT COLUMN ── --}}
    <div>

        {{-- Description --}}
        <div class="tm-card" style="padding:1.5rem;margin-bottom:1.25rem;">
            <h2 style="font-size:0.95rem;font-weight:700;color:var(--color-gray-900);
                       margin:0 0 0.75rem;">
                Description
            </h2>
            @if($task->description)
                <div id="show-description"
                     style="font-size:0.875rem;color:var(--color-gray-700);
                            line-height:1.7;white-space:pre-wrap;">{{ $task->description }}</div>
            @else
                <p style="font-size:0.875rem;color:var(--color-gray-400);
                          font-style:italic;margin:0;" id="show-description">
                    No description provided.
                </p>
            @endif
        </div>

        {{-- Quick status update card --}}
        <div class="tm-card" style="padding:1.25rem;margin-bottom:1.25rem;">
            <h2 style="font-size:0.9rem;font-weight:700;color:var(--color-gray-900);
                       margin:0 0 0.85rem;display:flex;align-items:center;gap:0.4rem;">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9
                             m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Quick Status Update
            </h2>
            <form method="POST"
                  action="{{ route('tasks.updateStatus', $task) }}"
                  id="quick-status-form">
                @csrf
                @method('PATCH')
                <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                    <select name="status"
                            id="quick-status-select"
                            class="tm-input"
                            style="flex:1;min-width:160px;">
                        <option value="to_do"
                                {{ $task->status === 'to_do' ? 'selected' : '' }}>
                            To Do
                        </option>
                        <option value="in_progress"
                                {{ $task->status === 'in_progress' ? 'selected' : '' }}>
                            In Progress
                        </option>
                        <option value="completed"
                                {{ $task->status === 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>
                    </select>
                    <button type="submit" class="btn-primary" id="btn-update-status"
                            style="white-space:nowrap;">
                        Update Status
                    </button>
                </div>
            </form>
        </div>

        {{-- Timestamps footer --}}
        <p style="font-size:0.78rem;color:var(--color-gray-400);margin:0;">
            Last updated by <strong style="color:var(--color-gray-600);">{{ $task->user->name }}</strong>
            on {{ $task->updated_at->format('M d, Y') }} at {{ $task->updated_at->format('g:i A') }}
        </p>

    </div>

    {{-- ── RIGHT SIDEBAR ── --}}
    <div>

        {{-- Activity Feed (static, since there's no comments table) --}}
        <div class="tm-card" style="padding:1.25rem;margin-bottom:1.25rem;">
            <h2 style="font-size:0.9rem;font-weight:700;color:var(--color-gray-900);
                       margin:0 0 1rem;">
                Activity Feed
            </h2>
            <div id="activity-feed">
                {{-- Created event --}}
                <div style="display:flex;gap:0.65rem;margin-bottom:0.9rem;">
                    <div class="tm-avatar" style="width:28px;height:28px;font-size:0.7rem;flex-shrink:0;">
                        {{ strtoupper(substr($task->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p style="font-size:0.8rem;color:var(--color-gray-700);margin:0 0 0.15rem;
                                  font-weight:500;line-height:1.4;">
                            <strong>{{ $task->user->name }}</strong> created this task
                        </p>
                        <p style="font-size:0.72rem;color:var(--color-gray-400);margin:0;">
                            {{ $task->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>

                {{-- Status change event --}}
                <div style="display:flex;gap:0.65rem;margin-bottom:0.9rem;">
                    <div class="tm-avatar" style="width:28px;height:28px;font-size:0.7rem;
                                                  flex-shrink:0;background:#0EA5E9;">
                        {{ strtoupper(substr($task->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p style="font-size:0.8rem;color:var(--color-gray-700);margin:0 0 0.15rem;
                                  font-weight:500;line-height:1.4;">
                            <strong>{{ $task->user->name }}</strong>
                            set status to <span class="badge {{ $badgeClass }}" style="font-size:0.7rem;">
                                {{ $statusLabel }}
                            </span>
                        </p>
                        <p style="font-size:0.72rem;color:var(--color-gray-400);margin:0;">
                            {{ $task->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Danger Zone: Delete --}}
        <div class="tm-card" style="padding:1.25rem;border-color:#fecaca;">
            <h2 style="font-size:0.9rem;font-weight:700;color:var(--color-danger);
                       margin:0 0 0.65rem;display:flex;align-items:center;gap:0.4rem;">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94
                             a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                Danger Zone
            </h2>
            <p style="font-size:0.8rem;color:var(--color-gray-500);margin:0 0 0.85rem;line-height:1.5;">
                Permanently delete this task. This action cannot be undone.
            </p>
            <form method="POST"
                  action="{{ route('tasks.destroy', $task) }}"
                  id="delete-task-form"
                  onsubmit="return confirm('Are you sure you want to delete this task? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="btn-danger"
                        id="btn-delete-task"
                        style="width:100%;justify-content:center;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6
                                 M10 11v6M14 11v6M9 6V4h6v2"/>
                    </svg>
                    Delete Task
                </button>
            </form>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
    // Collapse the two-column layout on small screens
    (function () {
        var grid = document.querySelector('[style*="grid-template-columns:1fr 320px"]');
        if (!grid) return;
        function applyLayout() {
            if (window.innerWidth < 768) {
                grid.style.gridTemplateColumns = '1fr';
            } else {
                grid.style.gridTemplateColumns = '1fr 320px';
            }
        }
        applyLayout();
        window.addEventListener('resize', applyLayout);
    })();
</script>
@endsection
