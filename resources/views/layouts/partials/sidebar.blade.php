<aside class="sidebar w-56 flex flex-col h-screen overflow-y-auto flex-shrink-0 p-4">

    {{-- Logo --}}
    <div class="mb-6">
        <div class="logo-font text-yellow-400 font-bold text-lg flex items-center gap-1">✦ heyToday!</div>
        <div class="text-xs text-slate-500 mt-0.5">Stay focused, get it done.</div>
    </div>

    {{-- My Lists --}}
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

    {{-- Add New List + Summary --}}
    <div class="mt-auto">
        <form method="POST" action="{{ route('lists.store') }}" class="flex gap-2 mb-4">
            @csrf
            <input name="name" placeholder="New list..." class="new-list-input flex-1">
            <button type="submit" class="add-list-btn">+</button>
        </form>

        @if(request('list_id'))
            @php
                $listId      = request('list_id');
                $totalTasks  = \App\Models\Task::where('list_id', $listId)->count();
                $doneTasks   = \App\Models\Task::where('list_id', $listId)->where('status', 2)->count();
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