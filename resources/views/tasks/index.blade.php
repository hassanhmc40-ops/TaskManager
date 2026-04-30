@extends('layouts.app')

@section('title', 'My Tasks')
@section('meta_description', 'Manage, filter, and track all your personal tasks in one place.')

@section('content')

{{-- ══════════════════════════════════════════════
     PAGE HEADER  (title + New Task button)
══════════════════════════════════════════════ --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.75rem;font-weight:700;color:var(--color-gray-900);margin:0 0 0.25rem;">
            My Tasks
        </h1>
        <p style="font-size:0.875rem;color:var(--color-gray-500);margin:0;">
            Manage, filter, and track your personal productivity.
        </p>
    </div>
    <a href="{{ route('tasks.create') }}" class="btn-primary" id="btn-new-task">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        New Task
    </a>
</div>

{{-- ══════════════════════════════════════════════
     FILTER BAR  (Status tabs + Category dropdown)
══════════════════════════════════════════════ --}}
<div class="tm-card" style="margin-bottom:1.25rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;padding:0.85rem 1.1rem;">

        {{-- Status filter tabs --}}
        <div style="display:flex;gap:0.4rem;flex-wrap:wrap;" role="tablist" aria-label="Filter by status">
            @php
                $currentStatus   = request('status');
                $currentCategory = request('category');

                // helper: build query keeping category param
                $filterUrl = fn($status) => route('tasks.index', array_filter([
                    'status'   => $status,
                    'category' => $currentCategory,
                ]));
            @endphp

            <a href="{{ route('tasks.index', array_filter(['category' => $currentCategory])) }}"
               id="filter-all"
               role="tab"
               aria-selected="{{ !$currentStatus ? 'true' : 'false' }}"
               style="
                   display:inline-flex;align-items:center;padding:0.4rem 0.9rem;
                   border-radius:8px;font-size:0.8rem;font-weight:600;
                   text-decoration:none;transition:all 0.15s;
                   {{ !$currentStatus
                       ? 'background:var(--color-primary);color:#fff;'
                       : 'background:var(--color-gray-100);color:var(--color-gray-600);' }}
               ">
                All
            </a>

            <a href="{{ $filterUrl('to_do') }}"
               id="filter-todo"
               role="tab"
               aria-selected="{{ $currentStatus === 'to_do' ? 'true' : 'false' }}"
               style="
                   display:inline-flex;align-items:center;padding:0.4rem 0.9rem;
                   border-radius:8px;font-size:0.8rem;font-weight:600;
                   text-decoration:none;transition:all 0.15s;
                   {{ $currentStatus === 'to_do'
                       ? 'background:var(--color-primary);color:#fff;'
                       : 'background:var(--color-gray-100);color:var(--color-gray-600);' }}
               ">
                To Do
            </a>

            <a href="{{ $filterUrl('in_progress') }}"
               id="filter-inprogress"
               role="tab"
               aria-selected="{{ $currentStatus === 'in_progress' ? 'true' : 'false' }}"
               style="
                   display:inline-flex;align-items:center;padding:0.4rem 0.9rem;
                   border-radius:8px;font-size:0.8rem;font-weight:600;
                   text-decoration:none;transition:all 0.15s;
                   {{ $currentStatus === 'in_progress'
                       ? 'background:var(--color-primary);color:#fff;'
                       : 'background:var(--color-gray-100);color:var(--color-gray-600);' }}
               ">
                In Progress
            </a>

            <a href="{{ $filterUrl('completed') }}"
               id="filter-completed"
               role="tab"
               aria-selected="{{ $currentStatus === 'completed' ? 'true' : 'false' }}"
               style="
                   display:inline-flex;align-items:center;padding:0.4rem 0.9rem;
                   border-radius:8px;font-size:0.8rem;font-weight:600;
                   text-decoration:none;transition:all 0.15s;
                   {{ $currentStatus === 'completed'
                       ? 'background:var(--color-primary);color:#fff;'
                       : 'background:var(--color-gray-100);color:var(--color-gray-600);' }}
               ">
                Completed
            </a>
        </div>

        {{-- Category filter dropdown --}}
        <form method="GET" action="{{ route('tasks.index') }}" id="category-filter-form"
              style="display:flex;align-items:center;gap:0.5rem;">
            @if($currentStatus)
                <input type="hidden" name="status" value="{{ $currentStatus }}">
            @endif

            <label for="category-filter" style="font-size:0.8rem;font-weight:600;color:var(--color-gray-600);display:flex;align-items:center;gap:0.35rem;">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 010 2H4a1 1 0 01-1-1zm3 6a1 1 0 011-1h10a1 1 0 010 2H7a1 1 0 01-1-1zm4 6a1 1 0 011-1h4a1 1 0 010 2h-4a1 1 0 01-1-1z"/>
                </svg>
                Category:
            </label>

            <select name="category" id="category-filter" class="tm-input"
                    onchange="this.form.submit()"
                    style="width:auto;min-width:110px;padding:0.35rem 0.7rem;font-size:0.8rem;">
                <option value="">All</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                            {{ $currentCategory == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </form>

    </div>
</div>

{{-- ══════════════════════════════════════════════
     TASKS TABLE
══════════════════════════════════════════════ --}}
<div class="tm-card" style="overflow-x:auto;">

    {{-- Table header --}}
    <table style="width:100%;border-collapse:collapse;" aria-label="Task list">
        <thead>
            <tr style="border-bottom:1px solid var(--color-gray-200);">
                <th style="padding:0.85rem 1.1rem;text-align:left;font-size:0.75rem;font-weight:700;
                           color:var(--color-gray-500);text-transform:uppercase;letter-spacing:.05em;
                           min-width:220px;">
                    Task Details
                </th>
                <th style="padding:0.85rem 1.1rem;text-align:left;font-size:0.75rem;font-weight:700;
                           color:var(--color-gray-500);text-transform:uppercase;letter-spacing:.05em;
                           white-space:nowrap;">
                    Category
                </th>
                <th style="padding:0.85rem 1.1rem;text-align:left;font-size:0.75rem;font-weight:700;
                           color:var(--color-gray-500);text-transform:uppercase;letter-spacing:.05em;
                           min-width:160px;">
                    Status
                </th>
                <th style="padding:0.85rem 1.1rem;text-align:left;font-size:0.75rem;font-weight:700;
                           color:var(--color-gray-500);text-transform:uppercase;letter-spacing:.05em;
                           white-space:nowrap;">
                    Due Date
                </th>
                <th style="padding:0.85rem 1.1rem;text-align:right;font-size:0.75rem;font-weight:700;
                           color:var(--color-gray-500);text-transform:uppercase;letter-spacing:.05em;">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody>

        {{-- ── Task rows ── --}}
        @forelse($tasks as $task)

            @php
                // Status badge style mapping
                $statusBadgeClass = [
                    'to_do'       => 'badge-todo',
                    'in_progress' => 'badge-inprogress',
                    'completed'   => 'badge-completed',
                ][$task->status] ?? 'badge-todo';

                $statusLabel = [
                    'to_do'       => 'To Do',
                    'in_progress' => 'In Progress',
                    'completed'   => 'Completed',
                ][$task->status] ?? $task->status;

                // Overdue detection: past due AND not completed
                $isOverdue = $task->due_date
                    && $task->due_date->isPast()
                    && $task->status !== 'completed';
            @endphp

            <tr style="border-bottom:1px solid var(--color-gray-100);transition:background 0.1s;"
                onmouseover="this.style.background='#FAFAFA'"
                onmouseout="this.style.background=''"
                id="task-row-{{ $task->id }}">

                {{-- Title + description --}}
                <td style="padding:1rem 1.1rem;vertical-align:top;">
                    <a href="{{ route('tasks.show', $task) }}"
                       style="font-weight:600;color:var(--color-gray-900);text-decoration:none;
                              font-size:0.9rem;display:block;margin-bottom:0.25rem;"
                       id="task-title-{{ $task->id }}">
                        {{ $task->title }}
                    </a>
                    @if($task->description)
                        <p style="font-size:0.8rem;color:var(--color-gray-500);margin:0;
                                  display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;
                                  overflow:hidden;">
                            {{ $task->description }}
                        </p>
                    @endif
                </td>

                {{-- Category badge --}}
                <td style="padding:1rem 1.1rem;vertical-align:top;">
                    @if($task->category)
                        <span class="badge badge-category" id="task-category-{{ $task->id }}">
                            {{ $task->category->name }}
                        </span>
                    @else
                        <span style="font-size:0.8rem;color:var(--color-gray-400);">—</span>
                    @endif
                </td>

                {{-- Quick status update --}}
                <td style="padding:1rem 1.1rem;vertical-align:top;">
                    {{-- Read-only badge --}}
                    <span class="badge {{ $statusBadgeClass }}" id="task-badge-{{ $task->id }}">
                        {{ $statusLabel }}
                    </span>

                    {{-- Quick-change form (auto-submits on change) --}}
                    <form method="POST"
                          action="{{ route('tasks.updateStatus', $task) }}"
                          id="status-form-{{ $task->id }}"
                          style="margin-top:0.4rem;">
                        @csrf
                        @method('PATCH')
                        <select name="status"
                                id="status-select-{{ $task->id }}"
                                aria-label="Change status for {{ $task->title }}"
                                onchange="document.getElementById('status-form-{{ $task->id }}').submit()"
                                style="font-size:0.72rem;color:var(--color-gray-500);
                                       border:none;background:none;cursor:pointer;
                                       padding:0;outline:none;">
                            <option disabled style="color:var(--color-gray-400);">Change Status ▾</option>
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
                    </form>
                </td>

                {{-- Due date (red if overdue) --}}
                <td style="padding:1rem 1.1rem;vertical-align:top;white-space:nowrap;">
                    @if($task->due_date)
                        <span style="display:inline-flex;align-items:center;gap:0.35rem;
                                     font-size:0.85rem;font-weight:500;
                                     color:{{ $isOverdue ? 'var(--color-danger)' : 'var(--color-gray-700)' }};"
                              id="task-due-{{ $task->id }}">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8"  y1="2" x2="8"  y2="6"/>
                                <line x1="3"  y1="10" x2="21" y2="10"/>
                            </svg>
                            {{ $task->due_date->format('M d, Y') }}
                            @if($isOverdue)
                                <span style="background:var(--color-danger);color:#fff;
                                             font-size:0.65rem;font-weight:700;padding:0.1rem 0.4rem;
                                             border-radius:4px;letter-spacing:.03em;">
                                    OVERDUE
                                </span>
                            @endif
                        </span>
                    @else
                        <span style="font-size:0.85rem;color:var(--color-gray-400);" id="task-due-{{ $task->id }}">
                            No due date
                        </span>
                    @endif
                </td>

                {{-- Actions: Edit | Delete | View --}}
                <td style="padding:1rem 1.1rem;vertical-align:top;text-align:right;">
                    <div style="display:inline-flex;align-items:center;gap:0.35rem;">

                        {{-- Edit --}}
                        <a href="{{ route('tasks.edit', $task) }}"
                           id="btn-edit-{{ $task->id }}"
                           title="Edit task"
                           style="display:inline-flex;align-items:center;justify-content:center;
                                  width:32px;height:32px;border-radius:7px;
                                  color:var(--color-gray-500);text-decoration:none;transition:all .15s;"
                           onmouseover="this.style.background='var(--color-primary-light)';this.style.color='var(--color-primary)'"
                           onmouseout="this.style.background='';this.style.color='var(--color-gray-500)'">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </a>

                        {{-- Delete --}}
                        <form method="POST"
                              action="{{ route('tasks.destroy', $task) }}"
                              id="delete-form-{{ $task->id }}"
                              onsubmit="return confirm('Are you sure you want to delete this task?')"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    id="btn-delete-{{ $task->id }}"
                                    title="Delete task"
                                    style="display:inline-flex;align-items:center;justify-content:center;
                                           width:32px;height:32px;border-radius:7px;border:none;
                                           background:none;cursor:pointer;color:var(--color-gray-500);
                                           transition:all .15s;"
                                    onmouseover="this.style.background='var(--color-danger-bg)';this.style.color='var(--color-danger)'"
                                    onmouseout="this.style.background='';this.style.color='var(--color-gray-500)'">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6M10 11v6M14 11v6M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </form>

                        {{-- View detail --}}
                        <a href="{{ route('tasks.show', $task) }}"
                           id="btn-view-{{ $task->id }}"
                           title="View task detail"
                           style="display:inline-flex;align-items:center;justify-content:center;
                                  width:32px;height:32px;border-radius:7px;
                                  color:var(--color-gray-500);text-decoration:none;transition:all .15s;"
                           onmouseover="this.style.background='var(--color-gray-100)';this.style.color='var(--color-gray-700)'"
                           onmouseout="this.style.background='';this.style.color='var(--color-gray-500)'">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/>
                            </svg>
                        </a>

                    </div>
                </td>

            </tr>

        {{-- ── Empty state ── --}}
        @empty
            <tr>
                <td colspan="5" style="padding:4rem 1.5rem;text-align:center;">
                    <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;">
                        <div style="width:56px;height:56px;background:var(--color-primary-light);
                                    border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <svg width="26" height="26" fill="none" viewBox="0 0 24 24"
                                 stroke="var(--color-primary)" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                                         M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <p style="font-size:1rem;font-weight:600;color:var(--color-gray-700);margin:0;">
                            No tasks found
                        </p>
                        <p style="font-size:0.875rem;color:var(--color-gray-400);margin:0;">
                            @if(request('status') || request('category'))
                                No tasks match your current filters.
                                <a href="{{ route('tasks.index') }}" style="color:var(--color-primary);text-decoration:none;font-weight:600;">
                                    Clear filters
                                </a>
                            @else
                                You have no tasks yet.
                            @endif
                        </p>
                        @if(!request('status') && !request('category'))
                        <a href="{{ route('tasks.create') }}" class="btn-primary" id="empty-create-btn"
                           style="margin-top:0.5rem;">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create your first task
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforelse

        </tbody>
    </table>

    {{-- ── Table footer: count + pagination ── --}}
    @if($tasks->count() > 0)
    <div style="display:flex;align-items:center;justify-content:space-between;
                flex-wrap:wrap;gap:0.75rem;padding:0.85rem 1.1rem;
                border-top:1px solid var(--color-gray-200);">
        <p style="font-size:0.8rem;color:var(--color-gray-500);margin:0;">
            Showing <strong>1</strong> to <strong>{{ $tasks->count() }}</strong>
            of <strong>{{ $tasks->count() }}</strong> task{{ $tasks->count() !== 1 ? 's' : '' }}
        </p>
        {{-- If you add ->paginate() in the controller, replace the above with: --}}
        {{-- {{ $tasks->withQueryString()->links() }} --}}
    </div>
    @endif

</div>

@endsection
