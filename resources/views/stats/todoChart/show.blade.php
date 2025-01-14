{{-- resources/views/stats/todoChart/show.blade.php --}}
<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Todo List Analytics</h1>
        
        <!-- Completion Rate Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-2">Overall Completion Rate</h2>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($completionRate, 1) }}%</p>
        </div>

        <!-- Main Charts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Priority Distribution -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Priority Distribution</h2>
                <canvas id="taskDistributionChart"
                    data-labels='@json($priorityDistribution->keys())'
                    data-values='@json($priorityDistribution->values())'>
                </canvas>
            </div>

            <!-- Completion Timeline -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Task Completion Timeline</h2>
                <canvas id="todoTaskCompletion"
                    data-labels='@json($completionTimeline->pluck("date")->map(function($date) {
                        return \Carbon\Carbon::parse($date)->format("M d");
                    }))'
                    data-values='@json($completionTimeline->pluck("count"))'>
                </canvas>
            </div>

            <!-- Punctuality Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Task Punctuality</h2>
                <canvas id="breakPatternsChart"
                    data-labels='@json(array_keys($punchualityStats))'
                    data-values='@json(array_values($punchualityStats))'>
                </canvas>
            </div>
            
            <!-- Upcoming Deadlines -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Upcoming Deadlines</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">Task</th>
                                <th class="px-4 py-2 text-left">Due Date</th>
                                <th class="px-4 py-2 text-left">Priority</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($upcomingDeadlines as $todo)
                                <tr>
                                    <td class="px-4 py-2">{{ $todo->task }}</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($todo->due_date)->format('M d, Y') }}</td>
                                    <td class="px-4 py-2">
                                    @switch($todo->priority)
                                        @case(0)
                                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Low</span>
                                            @break
                                        @case(1)
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Medium</span>
                                            @break
                                        @case(2)
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">High</span>
                                            @break
                                    @endswitch
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-center text-gray-500">No upcoming deadlines</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/chart.js'])
</x-app-layout>