<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Your Pomodoro Statistics</h1>
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Total Focus Hours -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Total Focus Hours</h2>
                <p class="text-3xl font-bold text-blue-600">{{ number_format($totalFocusHours, 1) }}</p>
            </div>
            
            <!-- Completion Rate -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Completion Rate</h2>
                <p class="text-3xl font-bold text-green-600">{{ number_format($completionRate, 1) }}%</p>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Task Distribution -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Task Distribution</h2>
                <canvas id="taskDistributionChart"
                    data-labels='@json($taskDistribution->pluck("task_name"))'
                    data-values='@json($taskDistribution->pluck("count"))'>
                </canvas>
            </div>

            <!-- Focus Time Trend -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Focus Time Trend</h2>
                <canvas id="focusTimeChart"
                    data-labels='@json($focusTimeByDay->pluck("date")->map(function($date) {
                        return \Carbon\Carbon::parse($date)->format("M d");
                    }))'
                    data-values='@json($focusTimeByDay->pluck("total_minutes"))'>
                </canvas>
            </div>

            <!-- Productive Time Slots -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Most Productive Hours</h2>
                <canvas id="productiveHoursChart"
                    data-labels='@json($productiveTimeSlots->pluck("hour"))'
                    data-values='@json($productiveTimeSlots->pluck("session_count"))'>
                </canvas>
            </div>

            <!-- Break Patterns -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Break Distribution</h2>
                <canvas id="breakPatternsChart"
                    data-labels='@json($breakPatterns->pluck("type"))'
                    data-values='@json($breakPatterns->pluck("count"))'>
                </canvas>
            </div>
        </div>
    </div>

    @vite(['resources/js/chart.js'])
</x-app-layout>