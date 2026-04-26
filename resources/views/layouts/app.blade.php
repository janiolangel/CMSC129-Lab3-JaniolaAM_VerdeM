<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>heyToday!</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    @include('layouts.partials.styles')
</head>

<body class="h-screen flex overflow-hidden">

    @include('layouts.partials.sidebar')

    <main class="flex-1 overflow-y-auto p-6">

        {{-- Top Bar --}}
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
                        @if(request('sort'))  <input type="hidden" name="sort"  value="{{ request('sort') }}">  @endif
                        @if(request('order')) <input type="hidden" name="order" value="{{ request('order') }}"> @endif
                        <input type="text" name="search" placeholder="Search tasks..." value="{{ request('search') }}" class="text-sm" style="width:200px">
                        <button type="submit" class="btn-yellow text-xs px-3">🔍</button>
                    </form>
                    @php
                        $activeCount = \App\Models\Task::where('list_id', request('list_id'))
                            ->where('status', '!=', 2)
                            ->whereNull('deleted_at')
                            ->count();
                    @endphp
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

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="alert-success">✓ {{ session('success') }}</div>
        @endif

        {{-- Page Content --}}
        @yield('content')

    </main>

    @include('layouts.partials.chat-widget')

</body>
</html>