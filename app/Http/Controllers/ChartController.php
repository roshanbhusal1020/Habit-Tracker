<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    private function mapMoodValue($value)
    {
        return match ($value) {
            'positive' => 3,
            'neutral' => 2,
            'negative' => 1,
            default => 0, // Default to Error
        };
    }

    private function mapProductivityValue($value)
    {
        return match ($value) {
            'productive' => 3,
            'moderate' => 2,
            'unproductive' => 1,
            default => 0, // Default to Error or Unknown
        };
    }

    private function fetchHabitData($userId, $type, $valueField = 'value')
    {
        return DB::table('habit_entries')
            ->join('habits', 'habit_entries.habit_id', '=', 'habits.id')
            ->where('habit_entries.user_id', $userId)
            ->where('habits.type', $type)
            ->selectRaw("DATE(habit_entries.entry_date) as entry_date, habit_entries.$valueField AS value")
            ->get()
            ->pluck('value', 'entry_date');
    }

    private function fetchJournalData($userId)
    {




        return DB::table('journals')
            ->where('user_id', $userId)
            ->selectRaw("DATE(created_at) as entry_date, id")
            ->pluck('id', 'entry_date');
    }

    private function fetchPomodoroData($userId)
    {
        return DB::table('pomodoro_sessions')
            ->where('user_id', $userId)
            ->selectRaw("DATE(created_at) as entry_date, COUNT(id) as session_count")
            ->groupByRaw("DATE(created_at)")
            ->pluck('session_count', 'entry_date');
    }

    private function combineAndMapDates($startDate, $endDate, $datasets, $mapCallback, $filterCallback = null)
    {
        $allDates = collect($datasets)
            ->flatMap(fn($data) => $data->keys()->all())
            ->unique()
            ->filter(fn($date) => $date >= $startDate && $date <= $endDate);

        $mappedDates = $allDates->map($mapCallback);

        // Apply additional filtering if a filter callback is provided
        if ($filterCallback) {
            $mappedDates = $mappedDates->filter($filterCallback);
        }

        return $mappedDates;
    }


    public function moodVsHabits(Request $request)
    {
        $userId = $request->user()->id;

        $moodData = $this->fetchHabitData($userId, 'mood');
        $completionData = DB::table('habit_entries')
            ->join('habits', 'habit_entries.habit_id', '=', 'habits.id')
            ->where('habit_entries.user_id', $userId)
            ->whereNotIn('habits.type', ['mood', 'note', 'productivity'])
            ->selectRaw("DATE(habit_entries.entry_date) as entry_date, MAX(CASE WHEN habit_entries.value = '1' THEN 1 ELSE 0 END) as completed")
            ->groupBy('habit_entries.entry_date')
            ->pluck('completed', 'entry_date');

        $startDate = now()->startOfMonth()->toDateString();
        $endDate = now()->endOfMonth()->toDateString();

        $result = $this->combineAndMapDates($startDate, $endDate, [$moodData, $completionData], function ($date) use ($moodData, $completionData) {
            return [
                'date' => $date,
                'mood' => isset($moodData[$date]) ? $this->mapMoodValue($moodData[$date]) : 0,
                'completed' => $completionData[$date] ?? 0,
            ];
        });

        return response()->json($result->values());
    }

    public function moodVsJournal(Request $request)
    {
        $userId = $request->user()->id;

        // Fetch mood and journal data
        $moodData = $this->fetchHabitData($userId, 'mood');
        $journalData = $this->fetchJournalData($userId);

        $startDate = now()->startOfMonth()->toDateString();
        $endDate = now()->endOfMonth()->toDateString();

        // Merge and map the data
        $result = $this->combineAndMapDates($startDate, $endDate, [$moodData, $journalData], function ($date) use ($moodData, $journalData) {
            return [
                'date' => $date,
                'mood' => isset($moodData[$date]) ? $this->mapMoodValue($moodData[$date]) : 0,
                'completed' => isset($journalData[$date]) ? 1 : 0, // Mark as completed if journal entry exists
            ];
        });

        return response()->json($result->values());
    }

    public function pomodoroVsProductivity(Request $request)
    {
        $userId = $request->user()->id;

        // Fetch productivity and pomodoro data
        $productivityData = $this->fetchHabitData($userId, 'productivity');
        $pomodoroData = $this->fetchPomodoroData($userId);

        $startDate = now()->startOfMonth()->toDateString();
        $endDate = now()->endOfMonth()->toDateString();

        $result = $this->combineAndMapDates(
            $startDate,
            $endDate,
            [$productivityData, $pomodoroData],
            function ($date) use ($productivityData, $pomodoroData) {
                return [
                    'date' => $date,
                    'productivity' => isset($productivityData[$date]) ? $this->mapProductivityValue($productivityData[$date]) : 0,
                    'completed' => isset($pomodoroData[$date]) ? 1 : 0, // Mark as completed if pomodoro entry exists
                ];
            },
            // Filter out entries with productivity = 0
            fn($item) => $item['productivity'] > 0
        );

        dump($pomodoroData);
        return response()->json($result->values());
    }

    public function journalVsProductivity(Request $request)
    {
        $userId = $request->user()->id;

        // Fetch productivity and journal data
        $productivityData = $this->fetchHabitData($userId, 'productivity');
        $journalData = $this->fetchJournalData($userId);
        // dump($journalData);
        // dump($productivityData);







        $startDate = now()->startOfMonth()->toDateString();
        $endDate = now()->endOfMonth()->toDateString();




                // dump($startDate);
        // dump($endDate);
        $result = $this->combineAndMapDates(
            $startDate,
            $endDate,
            [$productivityData, $journalData],
            function ($date) use ($productivityData, $journalData) {
                return [
                    'date' => $date,
                    'productivity' => isset($productivityData[$date]) ? $this->mapProductivityValue($productivityData[$date]) : 0,
                    'completed' => isset($journalData[$date]) ? 1 : 0, // Mark as completed if journal entry exists
                ];
            },
            // Filter out entries with productivity = 0
            fn($item) => $item['productivity'] > 0
        );

        return response()->json($result->values());
    }


}