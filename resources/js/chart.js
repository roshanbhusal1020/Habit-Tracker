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