<?php

use App\Http\Controllers\ChartController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Group API routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/mood-vs-habits', [ChartController::class, 'moodVsHabits']);
    // Route::get('/mood-vs-journal', [ChartController::class, 'moodVsJournal']);
    // Route::get('/pomodoro-vs-productivity', [ChartController::class, 'pomodoroVsProductivity']);
    // Route::get('/journal-vs-productivity', [ChartController::class, 'journalVsProductivity']);

    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
});


Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Generate a Sanctum token
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

    return response()->json(['error' => 'Invalid credentials'], 401);
});