<x-app-layout>
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Daily Overview with gradient background -->
        <div class="mb-8 bg-gradient-to-r from-emerald-500 to-emerald-700 rounded-2xl p-8 text-white shadow-lg">
            <h1 class="text-3xl font-bold">Welcome back!</h1>
            <p class="text-emerald-100">
                {{ now()->format('l, F j') }} - Your daily progress awaits
            </p>
        </div>

        <!-- Mood and Productivity Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Productivity -->
            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                <label class="block text-lg font-semibold text-gray-900 mb-3">Today's Productivity</label>
                <select 
                    class="form-select w-full rounded-lg border-gray-200 focus:ring-2 focus:ring-emerald-500"
                    onchange="saveMood('productivity', this.value, {{$productivityHabit->id}}, '{{ now()->format('Y-m-d') }}')">
                    <option value="">Select</option>
                    @foreach(['productive' => '✅ Productive', 'moderate' => '⚡ Moderately Productive', 'unproductive' => '💤 Unproductive'] as $value => $label)
                        <option value="{{$value}}"
                            @if(isset($entries[now()->format('Y-m-d')]))
                                @foreach($entries[now()->format('Y-m-d')] as $entry)
                                    @if($entry->habit_id == $productivityHabit->id && $entry->value == $value)
                                        selected
                                    @endif
                                @endforeach
                            @endif
                        >{{$label}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Mood -->
            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                <label class="block text-lg font-semibold text-gray-900 mb-3">Today's Mood</label>
                <select 
                    class="form-select w-full rounded-lg border-gray-200 focus:ring-2 focus:ring-emerald-500"
                    onchange="saveMood('mood', this.value, {{$moodHabit->id}}, '{{ now()->format('Y-m-d') }}')">
                    <option value="">Select</option>
                    @foreach(['positive' => '😊 Positive', 'neutral' => '😐 Neutral', 'negative' => '😢 Negative'] as $value => $label)
                        <option value="{{$value}}"
                            @if(isset($entries[now()->format('Y-m-d')]))
                                @foreach($entries[now()->format('Y-m-d')] as $entry)
                                    @if($entry->habit_id == $moodHabit->id && $entry->value == $value)
                                        selected
                                    @endif
                                @endforeach
                            @endif
                        >{{$label}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Notes -->
            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                <label class="block text-lg font-semibold text-gray-900 mb-3">Today's Note</label>
                <input 
                    type="text"
                    class="form-input w-full rounded-lg border-gray-200 focus:ring-2 focus:ring-emerald-500"
                    placeholder="Add note..."
                    onchange="saveMood('note', this.value, {{$noteHabit->id}}, '{{ now()->format('Y-m-d') }}')"
                    @if(isset($entries[now()->format('Y-m-d')]))
                        @foreach($entries[now()->format('Y-m-d')] as $entry)
                            @if($entry->habit_id == $noteHabit->id && $entry->note)
                                value="{{$entry->note}}"
                            @endif
                        @endforeach
                    @endif
                >
            </div>
        </div>

        <!-- Quick Stats Cards with gradients -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Focus Timer Card -->
            <a href="{{route('pomodoro.show')}}" 
               class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                <div class="flex justify-between items-start">
                    <div>
                        <i class="fas fa-clock text-3xl mb-3"></i>
                        <h3 class="text-lg font-semibold">Focus Timer</h3>
                        <p class="text-orange-100">Start Session</p>
                    </div>
                </div>
            </a>

            @php
            $todosNumber = $todos->count();

            $regularHabits = $habits->filter(function($habit) use ($noteHabit, $productivityHabit, $moodHabit){
                return !in_array($habit->id, [$noteHabit->id, $productivityHabit->id, $moodHabit->id]);
            }); 
    
            $totalHabits = $regularHabits->count(); 

            $completedHabits = 0;


            if(isset($entries[now()->format('Y-m-d')])) {
                $completedHabits = $entries[now()->format('Y-m-d')]
                    ->filter(function($entry) use ($noteHabit, $productivityHabit, $moodHabit) {
                        return !in_array($entry->habit_id, [$noteHabit->id, $productivityHabit->id, $moodHabit->id])
                            && $entry->value == 1;
                    })->count();
             }
            @endphp
            <!-- Tasks Overview Card -->
            <a href="{{route('todo.index')}}" 
               class="bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                <i class="fas fa-tasks text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Tasks</h3>
                <p class="text-sky-100">{{ $todosNumber }} remaining today</p>
            </a>

            <!-- Habits Card -->
            <a href="{{route('habits.show')}}" 
               class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                <i class="fas fa-chart-line text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Habits</h3>
                <p class="text-emerald-100">{{ $completedHabits }}/{{ $totalHabits }} completed</p>
            </a>

            <!-- Journal Card -->
            <a href="{{route('journal.show')}}" 
               class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                <i class="fas fa-book text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold">Journal</h3>
                <p class="text-violet-100">Write today's entry</p>
            </a>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Current Tasks -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Current Tasks</h2>
                        <a href="{{route('todo.index')}}" class="text-emerald-600 hover:text-emerald-700 font-medium">View All</a>
                    </div>
                    <div class="space-y-4">
                        @foreach($todos as $todo)
                            @if ($todo->completed == 0)
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                <div class="flex items-center gap-4">
                                    <form action="{{ route('todos.toggle', $todo->id) }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <input type="checkbox"
                                               class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500"
                                               name="completed" 
                                               onchange="this.form.submit()"
                                               {{ $todo->completed ? 'checked' : '' }}>
                                        <span class="ml-3">{{ $todo->task }}</span>
                                    </form>
                                </div>
                                <div class="flex items-center gap-4">
                                    <p class="text-sm text-gray-500">{{$todo->due_date}}</p>
                                    <span class="text-sm px-3 py-1 rounded-full 
                                        {{ $todo->priority == 2 ? 'bg-red-100 text-red-800' : 
                                           ($todo->priority == 1 ? 'bg-yellow-100 text-yellow-800' : 
                                                       'bg-green-100 text-green-800') }}">
                                        {{ $todo->priority == 2 ? 'High' : ($todo->priority == 1 ? 'Medium' : 'Low') }}
                                    </span>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Daily Habits -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Daily Habits</h2>
                        <a href="{{route('habits.show')}}" class="text-emerald-600 hover:text-emerald-700 font-medium">Manage Habits</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($habits as $habit)
                            @if (!in_array($habit->name, ['Mood', 'Productivity', 'Note']))
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                    <span class="font-medium">{{ $habit->name }}</span>
                                    <input 
                                        type="checkbox" 
                                        onchange="saveEntry('{{ $habit->name }}', this, {{ $habit->id }}, '{{ now()->format('Y-m-d') }}')"
                                        class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500"
                                        @if(isset($entries[now()->format('Y-m-d')]))
                                            @foreach($entries[now()->format('Y-m-d')] as $entry)
                                                @if($entry->habit_id == $habit->id && $entry->value == 1)
                                                    checked
                                                @endif
                                            @endforeach
                                        @endif
                                    >
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Side Column -->
            <div class="space-y-8">
                <!-- Progress Overview -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Progress Overview</h2>
                    <div class="space-y-6">
                        @foreach([
                                ['label' => 'Task Completion', 'value' => 70, 'icon' => 'fas fa-check-circle'],
                                ['label' => 'Habit Streak', 'value' => 85, 'icon' => 'fas fa-chart-line'],
                                ['label' => 'Focus Time', 'value' => 60, 'icon' => 'fas fa-clock']
                            ] as $stat)
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <i class="{{ $stat['icon'] }} text-emerald-600"></i>
                                        <span class="text-gray-600">{{ $stat['label'] }}</span>
                                    </div>
                                    <span class="font-medium">{{ $stat['value'] }}%</span>
                                </div>
                                <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-600 rounded-full transition-all duration-500"
                                         style="width: {{ $stat['value'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Recent Activity</h2>
                    <div class="space-y-4">
                        @foreach([
                                ['title' => 'Completed focus session', 'time' => '2h ago', 'icon' => 'fas fa-clock'],
                                ['title' => 'Added new task', 'time' => '3h ago', 'icon' => 'fas fa-plus-circle'],
                                ['title' => 'Updated habit streak', 'time' => '5h ago', 'icon' => 'fas fa-chart-line']
                            ] as $activity)
                            <div class="flex items-center gap-4">
                                <div class="p-2 bg-slate-100 rounded-lg">
                                    <i class="{{ $activity['icon'] }} text-emerald-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $activity['title'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $activity['time'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>

    

    <script>
    function saveEntry(name, input, habitId, date) {

        console.log('Trying to send', {name, input, habitId, date})
        fetch('/habits/entries/store', {
            method: 'POST', 
            headers:  {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        credentials: 'same-origin',
        body: JSON.stringify({
            name: name,
            habit_id: habitId, 
            date:date, 
            value: input.checked ? 1:0
        })
        })
        .then (response=>response.json())
        .then(data=> {
            console.log('Success:', data)
        })
        .catch(error => {
            console.error('Error:', error)
        })
        }

        function saveMood(name, value, habitId, date) {
            // console.log(date);
            console.log('name: '+ name + " value: " + value + ' habitId: ' + habitId + ' date: ' + date);
            fetch('/habits/entries/store', {
            method: 'POST', 
            headers:  {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        credentials: 'same-origin',
        body: JSON.stringify({
            name: name,
            habit_id: habitId, 
            date:date, 
            value: value
        })
        })
        .then (response=>response.json())
        .then(data=> {
            console.log('Success:', data)
        })
        .catch(error => {
            console.error('Error:', error)
        })
         
    }
    </script>
                        </x-app-layout>
