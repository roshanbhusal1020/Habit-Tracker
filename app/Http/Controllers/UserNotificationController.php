<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserNotificationController extends Controller
{

    public function show() {
        $user = Auth::user();

        if($user) {
            $paginator = UserNotification::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->paginate(19);
        }

        return response()->json($paginator);
    }

    // public function create ($user_id, $type, $content, $url, $read, $data) {

    //     if(auth()->user()) {
    //          $CreateUserNotification = UserNotification::create([
    //                                                             'user_id'=>$user_id,
    //                                                             'type' => $type,
    //                                                             'content'=>$content,
    //                                                             'url' => $url,
    //                                                             'read'=>$read, 

    //                                                         ]);
    //     }

    // }

    public function markAllNotificationRead(){

    }

    public function markSingleNotificationRead(){

    }
}
