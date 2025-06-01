<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class HabitStreakNotification extends Notification implements ShouldQueue {
    use Queueable;
                                        
    public function __construct(protected string $habitName, protected int $streakDays) {}

    public function via($notifiable): array {
        return ['database']; 
    }

    public function toArray($notifiable): array {
        return [
            'message' => "Great job! You've maintained a $this->streakDays-day streak for '$this->habitName'!", 
            'url' => route('habits.show')
        ];
    }
    public function id()
    {
        return null;
    }
}