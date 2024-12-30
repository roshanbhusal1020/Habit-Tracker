<?php

use App\Http\Controllers\Habitstable;
use App\Http\Controllers\HabitsTableController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PomodoroController;
use App\Http\Controllers\TodoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/', [Habitstable::class, 'show']);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/habits', [HabitsTableController::class, 'show'])->name('habits.show');
    Route::post('/habits/entry', [HabitsTableController::class, 'store'])->name('habits.store');

    // Route::post('/habits/entries/store', [HabitsTableController::class, 'storeEntry'])->name('habits.entries.store');
    Route::post('/habits/entries/store', [HabitsTableController::class, 'storeEntry'])->name('habits.entries.store');
    Route::get('/pomodoro', [PomodoroController::class, 'show'])->name('pomodoro.show');

    Route::post('/api/pomodoro/start', [PomodoroController::class, 'start'])->name('pomodoro.start');
    Route::post('/api/pomodoro/complete', [PomodoroController::class, 'complete'])->name('pomodoro.complete');

    Route::get('/todo', [TodoController::class, 'show'])->name('todo.show');



});

require __DIR__.'/auth.php';
