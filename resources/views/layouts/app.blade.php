<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>heyToday!</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="h-screen flex overflow-hidden">

<!-- Sidebar -->
<aside class="sidebar w-56 flex flex-col h-screen overflow-y-auto flex-shrink-0 p-4">

    <!-- Logo -->
    <div class="mb-6">
        <div class="logo-font text-yellow-400 font-bold text-lg flex items-center gap-1">
            ✦ heyToday!
        </div>
        <div class="text-xs text-slate-500 mt-0.5">Stay focused, get it done.</div>
    </div>

    <!-- My Lists -->
    <div class="mb-4">
        <div class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">My Lists</div>
        <div class="space-y-1">
            @foreach($lists as $list)
                <div class="list-item flex items-center justify-between px-2 py-2 {{ request('list_id') == $list->id ? 'active' : '' }}">
                    <a href="{{ route('tasks.index', ['list_id' => $list->id]) }}"
                       class="flex items-center gap-2 flex-1 text-sm {{ request('list_id') == $list->id ? 'text-green-400 font-semibold' : 'text-slate-300' }}">
                        <span class="text-xs">▪</span>
                        {{ $list->name }}
                    </a>
                    <form method="POST" action="{{ route('lists.destroy', $list) }}">
                        @csrf @method('DELETE')
                        <button class="text-slate-600 hover:text-red-400 text-xs ml-1" title="Delete list">✕</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Add New List -->
    <div class="mt-auto">
        <form method="POST" action="{{ route('lists.store') }}" class="flex gap-2 mb-4">
            @csrf
            <input name="name" placeholder="New list..." class="new-list-input flex-1">
            <button type="submit" class="add-list-btn">+</button>
        </form>

        <!-- Summary -->
        @if(request('list_id'))
        @php
            $listId = request('list_id');
            $totalTasks = \App\Models\Task::where('list_id', $listId)->count();
            $doneTasks = \App\Models\Task::where('list_id', $listId)->where('status', 2)->count();
            $activeTasks = \App\Models\Task::where('list_id', $listId)->where('status', '!=', 2)->count();
        @endphp
        <div>
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Summary</div>
            <div class="space-y-1.5 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Total</span>
                    <span class="summary-dot bg-slate-400"></span>
                    <span class="text-slate-300 font-semibold">{{ $totalTasks }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Done</span>
                    <span class="summary-dot bg-green-400"></span>
                    <span class="text-green-400 font-semibold">{{ $doneTasks }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Active</span>
                    <span class="summary-dot bg-yellow-400"></span>
                    <span class="text-yellow-400 font-semibold">{{ $activeTasks }}</span>
                </div>
            </div>
        </div>
        @endif
    </div>

</aside>

<!-- Main Content -->
<main class="flex-1 overflow-y-auto p-6">

    <!-- Top Bar -->
    @if(request('list_id'))
    @php $currentList = \App\Models\TaskList::find(request('list_id')); @endphp
    <div class="flex justify-between items-start mb-6">
        <div>
            <div class="text-xs text-slate-500 mb-0.5">{{ now()->format('l, d F Y') }}</div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span class="text-slate-500">▪</span>
                {{ $currentList->name ?? 'Tasks' }}
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" class="flex gap-2">
                <input type="hidden" name="list_id" value="{{ request('list_id') }}">
                @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                @if(request('order')) <input type="hidden" name="order" value="{{ request('order') }}"> @endif
                <input type="text" name="search" placeholder="Search tasks..." value="{{ request('search') }}" class="text-sm" style="width:200px">
                <button type="submit" class="btn-yellow text-xs px-3">🔍</button>
            </form>
            @php $activeCount = \App\Models\Task::where('list_id', request('list_id'))->where('status','!=',2)->whereNull('deleted_at')->count(); @endphp
            <span class="active-tasks-indicator">● {{ $activeCount }} active tasks</span>
        </div>
    </div>
    @else
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-white">Select a list to get started</h1>
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" placeholder="Search tasks..." value="{{ request('search') }}" class="text-sm" style="width:200px">
        </form>
    </div>
    @endif

    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif

    @yield('content')

</main>

<!-- Chat Widget Component -->
@include('components.chat-widget')

<!-- Chatbot JS -->
<script src="{{ asset('js/chatbot.js') }}"></script>

</body>
</html>