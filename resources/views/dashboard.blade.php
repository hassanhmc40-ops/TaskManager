@extends('layouts.app')

@section('title', 'Dashboard')
@section('meta_description', 'Your TaskManager dashboard — an overview of your tasks and productivity.')

@section('content')

{{-- ══════════════════════════════════════════════
     WELCOME HERO
══════════════════════════════════════════════ --}}
<div style="background:linear-gradient(135deg,#4F46E5 0%,#7C3AED 60%,#0EA5E9 100%);
            border-radius:16px;padding:2rem 2.25rem;margin-bottom:1.5rem;
            position:relative;overflow:hidden;">
    {{-- Decorative circles --}}
    <div style="position:absolute;top:-40px;right:-40px;width:220px;height:220px;
                background:rgba(255,255,255,0.06);border-radius:50%;pointer-events:none;"></div>
    <div style="position:absolute;bottom:-50px;right:120px;width:140px;height:140px;
                background:rgba(255,255,255,0.05);border-radius:50%;pointer-events:none;"></div>

    <div style="position:relative;z-index:1;display:flex;align-items:center;
                justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div>
            <p style="font-size:0.85rem;color:rgba(255,255,255,0.7);margin:0 0 0.3rem;font-weight:500;">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }},
            </p>
            <h1 style="font-size:1.75rem;font-weight:700;color:#fff;margin:0 0 0.5rem;">
                {{ Auth::user()->name }} 👋
            </h1>
            <p style="font-size:0.9rem;color:rgba(255,255,255,0.75);margin:0;">
                Here's what's on your plate today — stay focused and productive!
            </p>
        </div>
        <a href="{{ route('tasks.create') }}"
           id="dash-new-task-btn"
           style="display:inline-flex;align-items:center;gap:0.5rem;
                  background:#fff;color:var(--color-primary);font-weight:700;
                  font-size:0.875rem;padding:0.65rem 1.25rem;border-radius:10px;
                  text-decoration:none;box-shadow:0 4px 14px rgba(0,0,0,0.15);
                  transition:transform .15s,box-shadow .15s;white-space:nowrap;"
           onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,0.2)'"
           onmouseout="this.style.transform='';this.style.boxShadow='0 4px 14px rgba(0,0,0,0.15)'">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Task
        </a>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     QUICK-ACTION CARDS
══════════════════════════════════════════════ --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
            gap:1rem;margin-bottom:1.5rem;">

    @foreach([
        [
            'label'  => 'View All Tasks',
            'desc'   => 'See every task in your list',
            'icon'   => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
            'url'    => 'tasks.index',
            'color'  => '#4F46E5',
            'bg'     => '#EEF2FF',
            'id'     => 'dash-all-tasks',
        ],
        [
            'label'  => 'Create Task',
            'desc'   => 'Add a new task to your board',
            'icon'   => 'M12 4v16m8-8H4',
            'url'    => 'tasks.create',
            'color'  => '#059669',
            'bg'     => '#ECFDF5',
            'id'     => 'dash-create-task',
        ],
        [
            'label'  => 'In Progress',
            'desc'   => 'Tasks you are working on',
            'icon'   => 'M13 10V3L4 14h7v7l9-11h-7z',
            'url'    => 'tasks.index',
            'param'  => ['status' => 'in_progress'],
            'color'  => '#2563EB',
            'bg'     => '#EFF6FF',
            'id'     => 'dash-in-progress',
        ],
        [
            'label'  => 'Completed',
            'desc'   => 'Tasks you have finished',
            'icon'   => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'url'    => 'tasks.index',
            'param'  => ['status' => 'completed'],
            'color'  => '#16A34A',
            'bg'     => '#F0FDF4',
            'id'     => 'dash-completed',
        ],
    ] as $card)
    <a href="{{ route($card['url'], $card['param'] ?? []) }}"
       id="{{ $card['id'] }}"
       style="display:flex;flex-direction:column;gap:0.6rem;padding:1.25rem 1.35rem;
              background:#fff;border:1px solid var(--color-gray-200);border-radius:12px;
              text-decoration:none;transition:all .15s;cursor:pointer;"
       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,0.07)';this.style.borderColor='{{ $card['color'] }}'"
       onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--color-gray-200)'">
        <div style="width:40px;height:40px;background:{{ $card['bg'] }};border-radius:10px;
                    display:flex;align-items:center;justify-content:center;">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"
                 stroke="{{ $card['color'] }}" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
            </svg>
        </div>
        <div>
            <p style="font-size:0.875rem;font-weight:700;color:var(--color-gray-900);margin:0 0 0.15rem;">
                {{ $card['label'] }}
            </p>
            <p style="font-size:0.78rem;color:var(--color-gray-500);margin:0;">
                {{ $card['desc'] }}
            </p>
        </div>
    </a>
    @endforeach
</div>

{{-- ══════════════════════════════════════════════
     GETTING STARTED TIP
══════════════════════════════════════════════ --}}
<div class="tm-card" style="padding:1.5rem;display:flex;align-items:flex-start;
            gap:1rem;background:var(--color-primary-light);border-color:#c7d2fe;">
    <div style="width:42px;height:42px;background:var(--color-primary);border-radius:10px;
                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24"
             stroke="#fff" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3
                     m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547
                     A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531
                     c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
        </svg>
    </div>
    <div>
        <h2 style="font-size:0.95rem;font-weight:700;color:var(--color-primary);margin:0 0 0.3rem;">
            Getting started with TaskManager
        </h2>
        <p style="font-size:0.82rem;color:#4338CA;margin:0 0 0.75rem;line-height:1.6;">
            Create your first task, assign it a category and due date, then track its progress
            right from your task list. Use the status filters to stay focused on what matters most.
        </p>
        <a href="{{ route('tasks.create') }}"
           style="display:inline-flex;align-items:center;gap:0.4rem;
                  font-size:0.82rem;font-weight:600;color:var(--color-primary);text-decoration:none;">
            Create your first task
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</div>

@endsection
