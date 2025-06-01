import Chart from 'chart.js/auto';

// I could reuse these for other habuits
function createPieChart(elementId, labels, data) {
    const ctx = document.getElementById(elementId);
    if (ctx) {
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF'
                    ]
                }]
            }
        });
    }
}

function createLineChart(elementId, labels, data, label = 'Value') {
    const ctx = document.getElementById(elementId);
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    borderColor: '#36A2EB',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}

function createBarChart(elementId, labels, data, label = 'Value') {
    const ctx = document.getElementById(elementId);
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: '#36A2EB'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}

// rhis initialises the chart when the page is loaded
document.addEventListener('DOMContentLoaded', function() {

    //POMODORO START
    // Task Distribution Chart
    const taskChart = document.getElementById('taskDistributionChart');
    if (taskChart) {
        createPieChart(
            'taskDistributionChart', // this is used for todo, as well but I should organise this better for no confusion
            JSON.parse(taskChart.dataset.labels),
            JSON.parse(taskChart.dataset.values)
        );
    }

    // Focus Time Trend Chart
    const focusChart = document.getElementById('focusTimeChart');
    if (focusChart) {
        createLineChart(
            'focusTimeChart',
            JSON.parse(focusChart.dataset.labels),
            JSON.parse(focusChart.dataset.values),
            'Focus Minutes'
        );
    }

    // Productive Hours Chart
    const productiveChart = document.getElementById('productiveHoursChart');
    if (productiveChart) {
        createBarChart(
            'productiveHoursChart',
            JSON.parse(productiveChart.dataset.labels),
            JSON.parse(productiveChart.dataset.values),
            'Number of Sessions'
        );
    }

    // Break Patterns Chart
    const breakChart = document.getElementById('breakPatternsChart');
    if (breakChart) {
        createPieChart(
            'breakPatternsChart',
            JSON.parse(breakChart.dataset.labels),
            JSON.parse(breakChart.dataset.values)
        );
    }
    //POMODORO END

    //TODO START
    const todoTaskCompletion = document.getElementById('todoTaskCompletion');
    if(todoTaskCompletion) {
        createLineChart('todoTaskCompletion',
            JSON.parse(todoTaskCompletion.dataset.labels),
            JSON.parse(todoTaskCompletion.dataset.values),
            'Number of todos completed'

        );
    }


});


// In chart.js
function createMoodHabitsChart(elementId) {
    const ctx = document.getElementById(elementId);
    if (!ctx) return;
    
    const rawData = JSON.parse(ctx.dataset.stats);
    const moodStats = {1: {total: 0, count: 0}, 
                      2: {total: 0, count: 0}, 
                      3: {total: 0, count: 0}};
    
    rawData.forEach(day => {
        const moodRating = day.mood_rating;
        const habitKeys = Object.keys(day).filter(key => key.startsWith('habit_'));
        const completedHabits = habitKeys.reduce((sum, key) => sum + day[key], 0);
        
        moodStats[moodRating].total += completedHabits;
        moodStats[moodRating].count += 1;
    });
    
    const chartData = Object.entries(moodStats).map(([rating, stats]) => ({
        mood_rating: parseInt(rating),
        avg_habits: (stats.total / stats.count).toFixed(2)
    })).sort((a, b) => a.mood_rating - b.mood_rating);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Negative', 'Neutral', 'Positive'],
            datasets: [{
                label: 'Average Habits Completed',
                data: chartData.map(item => item.avg_habits),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: { y: { beginAtZero: true, max: 5 } }
        }
    });
}

// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    const moodHabitsChart = document.getElementById('moodHabitsChart');
    if (moodHabitsChart) {
        createMoodHabitsChart('moodHabitsChart');
    }
});