<x-app-layout>

 <div>
    
 </div>
 
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-4">Mood vs Habits Correlation</h2>
    <canvas id="moodHabitsChart" 
        data-stats='@json($transformedData)'>
    </canvas>

    <h1>As you can see the more you do habits your mood feels better. instead of waiting for your mood to get better and do the habit, instead do the habit and you will feel better.</h1>
</div>
 
@vite(['resources/js/chart.js'])


</x-app-layout>
