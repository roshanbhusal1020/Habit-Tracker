<x-app-layout>

<div class="py-12">
        <div class="flex justify-center items-center space-x-4 mb-6">
            <a href="{{route('habits.show', ['date'=>$previousMonth])}}" 
               class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                &larr; Previous Month <!-- larr is just left arrow '<-' similaryly rarr is right arrow -->
            </a> 
            <h2 class="text-2xl font-bold">{{$currentMonthDisplay}}</h2>
            <a href="{{route('habits.show', ['date'=>$nextMonth])}}" 
               class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                Next Month &rarr;
            </a>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" 
                     role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Error Message --}}
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" 
                     role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Add Habit Button --}}
            <div class="flex justify-between items-center mb-4">
                <button onclick="openHabitModal()" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                    Add New Habit
                </button>
            </div>

            {{-- Main Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                
                                {{-- Regular Habits --}}
                                @foreach($habits as $habit)
                                    @php
                                        $habitMonthYear = $habit->month_year ? Carbon\Carbon::parse($habit->month_year)->format('Y-m') : null;
                                        $targetMonthYear = $targetDate->format('Y-m');
                                        $isDeleted = $habit->deleted_from && Carbon\Carbon::parse($habit->deleted_from)->lte($targetDate);
                                    @endphp

                                    @if (!in_array($habit->name, ['Mood', 'Productivity', 'Note']) && 
                                        !$isDeleted &&
                                        ($habitMonthYear === null || $habitMonthYear === $targetMonthYear))
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $habit->name }}
                                            <button class="ml-2 text-red-500 hover:text-red-700"
                                                    onclick="deleteHabit({{ $habit->id }}, '{{ $habit->name }}')">
                                                ×
                                            </button>
                                        </th>
                                    @endif
                                @endforeach

                                {{-- Fixed Columns --}}
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Productivity
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mood
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Notes
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($dates as $date)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $date['formatted'] }}
                                    </td>

                                    {{-- Regular Habits Checkboxes --}}
                                    @foreach($habits as $habit)
                                        @php
                                            $habitMonthYear = $habit->month_year ? Carbon\Carbon::parse($habit->month_year)->format('Y-m') : null;
                                            $targetMonthYear = $targetDate->format('Y-m');
                                            $isDeleted = $habit->deleted_from && Carbon\Carbon::parse($habit->deleted_from)->lte($targetDate);
                                        @endphp

                                        @if (!in_array($habit->name, ['Mood', 'Productivity', 'Note']) && 
                                            !$isDeleted &&
                                            ($habitMonthYear === null || $habitMonthYear === $targetMonthYear))
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="checkbox" 
                                                    onchange="saveEntry('{{$habit->name}}', this, '{{$habit->id}}', '{{$date['full_date']}}')"
                                                    class="form-checkbox h-5 w-5 text-blue-600"
                                                    @if(isset($entries[$date['full_date']]))
                                                        @foreach($entries[$date['full_date']] as $entry)
                                                            @if($entry->habit_id == $habit->id && $entry->value == 1)
                                                                checked
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                >
                                            </td>
                                        @endif
                                    @endforeach

                                    {{-- Productivity Select --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <select class="form-select rounded-md shadow-sm mt-1 block w-full"
                                                onchange="saveMood('productivity', this.value, {{$productivityHabit->id}}, '{{$date['full_date']}}')">
                                            <option value="">Select</option>
                                            @foreach(['productive' => '✅ Productive', 'moderate' => '⚡ Moderately Productive', 'unproductive' => '💤 Unproductive'] as $value => $label)
                                                <option value="{{$value}}"
                                                    @if(isset($entries[$date['full_date']]))
                                                        @foreach($entries[$date['full_date']] as $entry)
                                                            @if($entry->habit_id == $productivityHabit->id && $entry->value == $value)
                                                                selected
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                >{{$label}}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    {{-- Mood Select --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <select class="form-select rounded-md shadow-sm mt-1 block w-full"
                                                onchange="saveMood('mood', this.value, {{$moodHabit->id}}, '{{$date['full_date']}}')">
                                            <option value="">Select</option>
                                            @foreach(['positive' => '😊 Positive', 'neutral' => '😐 Neutral', 'negative' => '😢 Negative'] as $value => $label)
                                                <option value="{{$value}}"
                                                    @if(isset($entries[$date['full_date']]))
                                                        @foreach($entries[$date['full_date']] as $entry)
                                                            @if($entry->habit_id == $moodHabit->id && $entry->value == $value)
                                                                selected
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                >{{$label}}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    {{-- Notes Input --}}
                                    <td class="px-6 py-4">
                                        <input type="text"
                                            class="form-input rounded-md shadow-sm mt-1 block w-full"
                                            placeholder="Add note..."
                                            onchange="saveMood('note', this.value, {{$noteHabit->id}}, '{{$date['full_date']}}')"
                                            @if(isset($entries[$date['full_date']]))
                                                @foreach($entries[$date['full_date']] as $entry)
                                                    @if($entry->habit_id == $noteHabit->id && $entry->note)
                                                        value="{{$entry->note}}"
                                                    @endif
                                                @endforeach
                                            @endif
                                        >
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Habit Modal --}}
    <div id="habitModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center">
                Add New Habit for {{ $currentMonthDisplay }}
            </h3>
            <form id="newHabitForm" class="mt-4" method="POST" action="{{ route('habits.store') }}">
                @csrf
                <input type="hidden" name="target_date" value="{{ $targetDate->format('Y-m-d') }}">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Habit Name</label>
                    <input type="text" name="name" required 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Type</label>
                    <select name="type" required 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="checkbox">Checkbox</option>
                        <option value="text">Text</option>
                        <option value="mood">Mood</option>
                    </select>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Create Habit
                    </button>
                    <button type="button" onclick="closeHabitModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Add this JavaScript -->
<script>
    function openHabitModal() {
        document.getElementById('habitModal').classList.remove('hidden');
    }

    function closeHabitModal() {
        document.getElementById('habitModal').classList.add('hidden');
    }
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
    

    function saveMood(name, value, habitId, date) { // I need to give better name as all mood, productivity and note use this
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

    function deleteHabit(habitId, habitName) {

        console.log(habitId, habitName)
        if(confirm(`Are you sure want to delete the habit "${habitName}" ?`)) {
            fetch(`/habits/${habitId}`, {  
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {

                    window.location.reload();
                } else {
                    alert('Failed to delete habit: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete habit');
            });
        }
    }
</script>
</x-app-layout>