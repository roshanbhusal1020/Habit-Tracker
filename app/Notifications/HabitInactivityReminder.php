<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;  


class HabitInactivityReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected ?array $inactiveHabits;
    protected bool $isGeneralReminder;

    public function __construct(?array $inactiveHabits, bool $isGeneralReminder = false)
    {
        $this->inactiveHabits = $inactiveHabits;
        $this->isGeneralReminder = $isGeneralReminder;
    }

    public function via($notifiable)
    {
        return ['database']; // Store notifications in database
    }

    public function toArray($notifiable)
    {
        $url = URL::to('/habits');  

        if ($this->isGeneralReminder) {
            return [
                'message' => "You haven't logged any habits yet. Start tracking to build a routine!",
                'url' => $url

            ];
        }

        return [
            'message' => "You haven't logged the following habits for a while: " . implode(', ', $this->inactiveHabits) . ". Keep going!",
            'url' => route('habits.show')

        ];
    }
    public function id()
    {
        return null;
    }
}