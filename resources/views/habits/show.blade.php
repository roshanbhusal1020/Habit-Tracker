<x-app-layout>
    <!-- @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif  NEED TO WORK ON THIS AS ITS IMPORTANT TO LET USER KNOW IT WAS SUCCESSFULLY ADDED A HABIT OR ANYTING -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header with Add Button -->
            <div class=" flex justify-between items-center">
                <h2 class="text-2xl font-bold">December 2024</h2>
                <button class="bg-red-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                    Add New Habit
                </button>
            </div>

            <!-- Main Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Working Hours
                                </th> -->
                                @foreach($habits as $habit)
                                   @if ($habit->name != "Mood" && $habit->name != "Productivity")
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" >{{ $habit->name }}</th>
                                       
                                   @endif
                                    
                                @endforeach
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Productivity
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mood
                                </th>
                                <!-- I need to add mood and producitivty sepatrtly and remove from the habit like the follwoing code -->
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Notes
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Day 1 -->
                        
                            <tbody class="bg-white divide-y divide-gray-200">
    <!-- Let's just show 5 days manually first -->
    @foreach ($dates as $date)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">
                {{ $date['formatted'] }}
            </td>
            <!-- <td class="px-6 py-4 whitespace-nowrap">
                <input type="text" 
                    class="form-input rounded-md shadow-sm mt-1 block w-full"
                    placeholder="9-17">
            </td> -->
            @foreach($habits as $habit)
                @if (($habit->name != 'Mood') && ($habit->name != 'Productivity'))
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="checkbox" 
                        onchange="saveEntry('{{$habit['name']}}', this, '{{$habit['id']}}', '{{$date['full_date']}}')"
                        data-habit= "{{$habit->id}}"
                        data-day="{{$date['full_date']}}"
                        class="form-checkbox h-5 w-5 text-blue-600" 
                     @if(isset($entries[$date['full_date']]))
                            @foreach($entries[$date['full_date']] as $entry)
                           @if ($entry['habit_id'] == $habit->id && $entry['value'] ==1)
                               checked
                           @endif
                                @endforeach                                
                            @endif
                        >
                   
                </td>      
                @endif
              
            @endforeach
            <td class="px-6 py-4 whitespace-nowrap">
    <select class="form-select rounded-md shadow-sm mt-1 block w-full" 
            onchange="saveMood('productivity', this.value, {{$habit->id}}, '{{$date['full_date']}}')">
        <option value="">Select</option>
        <option value="productive" 
            @if(isset($entries[$date['full_date']]))
                @foreach($entries[$date['full_date']] as $entry)
                    @if($entry->habit_id == $habit->id && $entry->value == 'productive')
                        selected
                    @endif
                @endforeach
            @endif
        >✅ Productive</option>
        <option value="moderate"
            @if(isset($entries[$date['full_date']]))
                @foreach($entries[$date['full_date']] as $entry)
                    @if($entry->habit_id == $habit->id && $entry->value == 'moderate')
                        selected
                    @endif
                @endforeach
            @endif
        >⚡ Moderately Productive</option>
        <option value="unproductive"
            @if(isset($entries[$date['full_date']]))
                @foreach($entries[$date['full_date']] as $entry)
                    @if($entry->habit_id == $habit->id && $entry->value == 'unproductive')
                        selected
                    @endif
                @endforeach
            @endif
        >💤 Unproductive</option>
    </select>
</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <select class="form-select rounded-md shadow-sm mt-1 block w-full" onchange="saveMood('mood',this.value, {{$habit->id}},  '{{$date['full_date']}}')">
                    <option value="">Select</option>
                    <option value="positive">😊 Positive</option>
                    <option value="neutral">😐 Neutral</option>
                    <option value="negative">😢 Negative</option>
                </select>
            </td>

            <td class="px-6 py-4">
                <input type="text"
                    class="form-input rounded-md shadow-sm mt-1 block w-full"
                    placeholder="Add note..."
                    onchange="saveMood('note', this.value, {{$habit->id}}, '{{$date['full_date']}}')"
                    
                     @if (isset($entries[$date['full_date']]))
                        @foreach ($entries[$date['full_date']] as $entry)
                        @if ($entry->habit_id == $habit->id && $entry->note )
                        value = "{{$entry['note']}}"

                        @endif
                        @endforeach
                     @endif
                    >
            </td>
        </tr>
    @endforeach
</tbody>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- In your blade file, update the button: -->
<button onclick="openHabitModal()" class="bg-red-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
    Add New Habit
</button>

<!-- Add this modal HTML at the bottom of your blade file -->
<div id="habitModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center">Add New Habit</h3>
            <form id="newHabitForm" class="mt-4" method="POST" action="{{ route('habits.store') }}">
                @csrf
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