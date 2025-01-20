<x-app-layout>


    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Daily Overview -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Welcome back!</h1>
            <p class="text-gray-600">
                {{ now()->format('l, F j') }} - Your daily progress awaits
            </p>
        </div>


        <h1>HOW WAS YOUR DAY ?? PRODUCTUVUTY , MOOD, SHORT NOTE</h1>

        <!-- Mood and Productivity Section -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <!-- Productivity -->
    <div class="bg-white rounded-lg p-4 shadow">
        <label class="block text-sm font-medium text-gray-700 mb-2">Today's Productivity</label>
        <select 
            class="form-select rounded-md shadow-sm mt-1 block w-full"
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
    <div class="bg-white rounded-lg p-4 shadow">
        <label class="block text-sm font-medium text-gray-700 mb-2">Today's Mood</label>
        <select 
            class="form-select rounded-md shadow-sm mt-1 block w-full"
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
    <div class="bg-white rounded-lg p-4 shadow">
        <label class="block text-sm font-medium text-gray-700 mb-2">Today's Note</label>
        <input 
            type="text"
            class="form-input rounded-md shadow-sm mt-1 block w-full"
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
        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Focus Timer Card -->
            <a href="{{route('pomodoro.show')}}" class="bg-orange-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <i data-lucide="timer" class="w-8 h-8 mb-3"></i>
                        <h3 class="text-lg font-semibold">Focus Timer</h3>
                        <p class="opacity-90">Start Session</p>
                    </div>

                    @if(false)
                        <span class="text-lg font-bold">25:00</span>
                    @endif
                </div>
            </a>
                        @php
                            $todosNumber = $todos->count();


                            @endphp
            <!-- Tasks Overview Card -->
            <a href="{{route('todo.index')}}" class="bg-sky-600 rounded-xl p-6 text-white shadow-lg">
                <i data-lucide="check-circle" class="w-8 h-8 mb-3"></i>
                <h3 class="text-lg font-semibold">Tasks</h3>
                <p class="opacity-90">

                {{ $todosNumber }} remaining today
                </p>
            </a>
        @php
    
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
            <!-- Habits Card -->
            <a href="{{route('habits.show')}}" class="bg-emerald-600 rounded-xl p-6 text-white shadow-lg">
                <i data-lucide="activity" class="w-8 h-8 mb-3"></i>
                <h3 class="text-lg font-semibold">Habits</h3>
                <p class="opacity-90">{{ $completedHabits }}/{{ $totalHabits }} completed</p>

            </a>

            <!-- Journal Card -->
            <a href="{{route('journal.show')}}" class="bg-violet-600 rounded-xl p-6 text-white shadow-lg">
                <i data-lucide="book-open" class="w-8 h-8 mb-3"></i>
                <h3 class="text-lg font-semibold">Journal</h3>
                <p class="opacity-90">Write today's entry</p>
            </a>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Current Tasks -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Current Tasks</h2>
                        <a href="{{route('todo.index')}}" class="text-emerald-600 hover:text-emerald-700">View All</a>
                    </div>
                    <div class="space-y-3">
@dump($todos)
@dump($entries)



@foreach($todos as $todo)
                            @if ($todo->completed ==0)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                <div class="flex items-center gap-3">
                                <form action="{{ route('todos.toggle', $todo->id) }}" method="POST">
                                @csrf
                                    @method('PATCH')
                                    <!-- <input type="hidden" name="id" value="{{ $todo->id }}">  -->

                                    <input type="checkbox"
                                    name="completed" 
                                    onchange="this.form.submit()"
                                    {{ $todo->completed ? 'checked' : '' }}>
                            </form>

                                    <span> {{ $todo->task }}</span>
                                </div>
                                <p> {{$todo->due_date}}</p>
                                <span class="text-sm px-2 py-1 rounded-full 
                                    {{ $todo->priority == 2 ? 'bg-red-100 text-red-800' : 
                                       ($todo->priority == 1 ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-green-100 text-green-800') }}">
                                    {{ $todo->priority == 2 ? 'High' : ($todo->priority == 1 ? 'Medium' : 'Low') }}
                                </span>
                            </div>
                            @endif
                       
                        @endforeach
                    </div>
                </div>
@dump($habits)
                <!-- Daily Habits -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Daily Habits</h2>
                        <a href="{{route('habits.show')}}" class="text-emerald-600 hover:text-emerald-700">Manage Habits</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    @foreach($habits as $habit)
                            @if (!in_array($habit->name, ['Mood', 'Productivity', 'Note']))
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                    <input 
                                        type="checkbox" 
                                        onchange="saveEntry('{{ $habit->name }}', this, {{ $habit->id }}, '{{ now()->format('Y-m-d') }}')"
                                        class="form-checkbox h-5 w-5 text-blue-600"
                                        @if(isset($entries[now()->format('Y-m-d')]))
                                            @foreach($entries[now()->format('Y-m-d')] as $entry)
                                                @if($entry->habit_id == $habit->id && $entry->value == 1)
                                                    checked
                                                @endif
                                            @endforeach
                                        @endif
                                    >
                                    <span>{{ $habit->name }}</span>
                                </div>
                            @endif
                        @endforeach

                    </div>
                </div>
            </div>

            <!-- Side Column -->
            <div class="space-y-8">
                <!-- Progress Overview -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Progress Overview</h2>
                    <div class="space-y-4">

                    @foreach([
                            ['label' => 'Task Completion', 'value' => 70, 'icon' => 'check-circle'],
                            ['label' => 'Habit Streak', 'value' => 85, 'icon' => 'activity'],
                            ['label' => 'Focus Time', 'value' => 60, 'icon' => 'timer']
                        ] as $stat)
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5 text-emerald-600"></i>
                                        <span class="text-gray-600">{{ $stat['label'] }}</span>
                                    </div>
                                    <span class="font-medium">{{ $stat['value'] }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-emerald-600 rounded-full h-2"
                                         style="width: {{ $stat['value'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Activity</h2>
                    <div class="space-y-4">

                    @foreach([
                            ['title' => 'Completed focus session', 'time' => '2h ago', 'icon' => 'timer'],
                            ['title' => 'Added new task', 'time' => '3h ago', 'icon' => 'plus-circle'],
                            ['title' => 'Updated habit streak', 'time' => '5h ago', 'icon' => 'activity']
                        ] as $activity)
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-slate-100 rounded-lg">
                                    <i data-lucide="{{ $activity['icon'] }}" class="w-5 h-5 text-emerald-600"></i>
                                </div>
                                <div>
                                    <p class="text-gray-900">{{ $activity['title'] }}</p>
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
