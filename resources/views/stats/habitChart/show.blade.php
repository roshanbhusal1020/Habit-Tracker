<x-app-layout>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Habit Tracker Dashboard</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Check if the token exists in the session and set it in localStorage
            if ('{{ session('api_token') }}') {
                localStorage.setItem('authToken', '{{ session('api_token') }}');
            }
        </script>

        <script>
            // Function to fetch chart data with Bearer token
            async function fetchChartData(url) {
                const token = localStorage.getItem('authToken'); // Retrieve token from storage
                const response = await fetch(url, {
                    headers: {
                        'Authorization': `Bearer ${token}`, // Add Bearer token
                        'Content-Type': 'application/json',
                    },
                });

                if (!response.ok) {
                    console.error(`Error fetching data from ${url}:`, response.statusText);
                }

                return response.json();
            }

            // Process the fetched chart data
            const processChartData = (data) => {
                const dataArray = Object.values(data);
                return {
                    dates: dataArray.map(item => item.date),
                    moods: dataArray.map(item => item.mood || 0),
                    completions: dataArray.map(item => item.completed || 0),
                    productivity: dataArray.map(item => item.productivity || 0),
                };
            };

            const generateCustomLabels = (chart, totalCount, type) => {
                const completions = chart.data.datasets[1].data; // Dataset 1 contains completions
                const values = chart.data.datasets[0].data;

                const completedValues = values.filter((_, index) => completions[index] === 1);
                const notCompletedValues = values.filter((_, index) => completions[index] !== 1);

                const getPercentages = (data, count) => {
                    if (count === 0) return { high: 0, medium: 0, low: 0 };

                    const high = (data.filter(value => value === 3).length / count) * 100;
                    const medium = (data.filter(value => value === 2).length / count) * 100;
                    const low = (data.filter(value => value === 1).length / count) * 100;

                    return {
                        high: high.toFixed(1),
                        medium: medium.toFixed(1),
                        low: low.toFixed(1),
                    };
                };

                const completedCount = completedValues.length;
                const notCompletedCount = notCompletedValues.length;

                const completedPercentages = getPercentages(completedValues, completedCount);
                const notCompletedPercentages = getPercentages(notCompletedValues, notCompletedCount);

                // Determine labels based on type (either "productivity" or "mood")
                const labels = type === "productivity"
                    ? { high: "Productive", medium: "Moderate", low: "Unproductive" }
                    : { high: "Positive", medium: "Neutral", low: "Negative" };

                // Custom labels
                return [
                    {
                        text: `✓ Completed: ${labels.high} ${completedPercentages.high}%, ${labels.medium} ${completedPercentages.medium}%, ${labels.low} ${completedPercentages.low}%`,
                        fillStyle: 'transparent', strokeStyle: 'transparent'
                    },
                    {
                        text: `✗ Not Completed: ${labels.high} ${notCompletedPercentages.high}%, ${labels.medium} ${notCompletedPercentages.medium}%, ${labels.low} ${notCompletedPercentages.low}%`,
                        fillStyle: 'transparent', strokeStyle: 'transparent'
                    }
                ];
            };


            // Common chart options
            const getChartOptions = (title, isProductivity = false) => ({
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Date' } },
                    y: {
                        title: { display: true, text: isProductivity ? 'Productivity Rating' : 'Mood Rating' },
                        min: 1,
                        max: 3,
                        ticks: {
                            stepSize: 1,
                            callback: (value) => {
                                const labels = isProductivity
                                    ? ['Error', 'Unproductive', 'Moderate', 'Productive']
                                    : ['Error', 'Negative', 'Neutral', 'Positive'];
                                return labels[value] || '';
                            },
                        },
                    },
                },
                plugins: {
                    title: {
                        display: true,
                        text: title,
                        font: { size: 18, weight: 'bold' },
                        color: '#333',
                        padding: { bottom: 40 }, // Space between title and legend/chart
                    },
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            filter: function (legendItem, chartData) {
                                // Hide datasets with no label
                                return legendItem.text !== undefined;
                            },
                            generateLabels: function (chart) {
                                const totalCount = chart.data.labels.length;

                                // Combine custom labels with default labels
                                const customLabels = generateCustomLabels(chart, totalCount, isProductivity ? "productivity" : "mood");
                                const defaultLabels = Chart.defaults.plugins.legend.labels.generateLabels(chart);

                                return [...customLabels, ...defaultLabels];
                            },
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                const dataset = tooltipItem.datasetIndex;
                                const index = tooltipItem.dataIndex;
                                const data = tooltipItem.chart.data.datasets[dataset].data;
                                const completions = tooltipItem.chart.data.datasets[1].data;
                                const completionText = completions[index] === 1 ? '✓ Completed' : '✗ Not Completed';

                                const moodLabels = ['Error', 'Negative', 'Neutral', 'Positive'];
                                const productivityLabels = ['Error', 'Unproductive', 'Moderate', 'Productive'];
                                const valueText = isProductivity
                                    ? productivityLabels[data[index]] || 'Error'
                                    : moodLabels[data[index]] || 'Error';

                                return `${completionText}, ${valueText}`;
                            },
                        },
                    },
                },
                layout: { padding: { bottom: 50 } },
            });

            // Register annotation plugin for checkmarks (✓) and crosses (✗)
            Chart.register({
                id: 'annotations',
                afterDatasetsDraw(chart) {
                    const ctx = chart.ctx;
                    const meta = chart.getDatasetMeta(0);
                    meta.data.forEach((point, index) => {
                        const completion = chart.data.datasets[1].data[index];
                        ctx.save();
                        ctx.font = '16px Arial';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillStyle = completion ? 'green' : 'red';
                        ctx.fillText(completion ? '✓' : '✗', point.x, point.y - 10);
                        ctx.restore();
                    });
                },
            });

            // Generic function to create a chart
            const createChart = async (canvasId, apiEndpoint, chartTitle, isProductivity = false) => {
                const data = await fetchChartData(apiEndpoint);
                const { dates, moods, completions, productivity } = processChartData(data);

                const ctx = document.getElementById(canvasId).getContext('2d');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: dates,
                        datasets: [
                            {
                                data: isProductivity ? productivity : moods,
                                borderColor: 'rgba(54, 162, 235, 1)',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderWidth: 2,
                                pointStyle: 'circle',
                                pointRadius: 2,
                            },
                            {
                                data: completions,
                                borderColor: 'transparent',
                                backgroundColor: 'transparent',
                                borderWidth: 0,
                                pointStyle: 'line',
                                pointRadius: 0,
                                showLine: false,
                            },
                        ],
                    },
                    options: getChartOptions(chartTitle, isProductivity),
                });
            };

            // Create charts dynamically
            createChart('moodHabitsChart', '/mood-vs-habits', 'Mood vs. Habits');
            createChart('moodJournalChart', '/mood-vs-journal', 'Mood vs. Journal');
            createChart('journalProductivityChart', '/journal-vs-productivity', 'Journal vs. Productivity', true);
            createChart('pomodoroProductivityChart', '/pomodoro-vs-productivity', 'Pomodoro vs. Productivity', true);
        </script>
    </head>

    <!-- Chart Navigation Buttons -->
    <div class="flex flex-wrap justify-center gap-4 mb-6 mt-4">
        <a href="{{ route('stats.pomodoro') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition-colors flex items-center">
        <i class="fas fa-clock text-3xl mb-3"></i>
            Pomodoro Stats
        </a>
        <a href="{{ route('stats.todo') }}" class="px-6 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition-colors flex items-center">
        <i class="fas fa-tasks text-3xl mb-3"></i>
            Todo Stats
        </a>
    </div>

    <!-- Result Table -->
    <div class="grid grid-cols-1">
        <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Habit
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Productivity Impact
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mood Impact
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($userHabits as $habit)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $habit->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($habit->productivity['percentage_increase'], 1) }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($habit->mood['percentage_increase'], 1) }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Progress & Tracking -->
    <div class="grid grid-cols-1">
        <!-- Today's Progress -->

        <!-- Mood vs. Habits Chart Card -->
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <canvas id="moodHabitsChart" class="w-full"></canvas>
        </div>

        <!-- Mood vs. Journal Chart Card -->
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <canvas id="moodJournalChart" class="w-full"></canvas>
        </div>

        <!-- Pomodoro vs. Productivity Chart Card -->
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <canvas id="pomodoroProductivityChart" class="w-full"></canvas>
        </div>

        <!-- Journal vs. Productivity Chart Card -->
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <canvas id="journalProductivityChart" class="w-full"></canvas>
        </div>
    </div>

</x-app-layout>
