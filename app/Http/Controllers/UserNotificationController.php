<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;

class UserNotificationController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if ($user) {
            $paginator = UserNotification::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->paginate(19);
        }

        return response()->json($paginator);
    }


    // public function markAllNotificationRead(){

    // }

    // public function markSingleNotificationRead(){

    // }
}
