<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;

// Route untuk Halaman Login menggunakan standard Blade view
Route::get('/', function () {
    return view('auth.login');
})->name('login');

// Route khusus Socialite & Logout
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

// Group Route yang butuh Login (Middleware Auth)
Route::middleware('auth')->group(function () {
    
    // Halaman Utama / Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Proses ToDo List
    Route::post('/todo-lists', [DashboardController::class, 'store'])->name('todo.store');
    Route::post('/todo-lists/join', [DashboardController::class, 'join'])->name('todo.join');
    Route::delete('/todo-lists/{id}', [DashboardController::class, 'destroy'])->name('todo.destroy');

    // Route Halaman Detail Ruangan
Route::get('/room/{share_token}', [RoomController::class, 'show'])->name('room.show');

// API Routes untuk aksi interaktif (Task & Chat)
Route::post('/room/{share_token}/task', [RoomController::class, 'storeTask'])->name('task.store');
Route::patch('/task/{task}', [RoomController::class, 'toggleTask'])->name('task.toggle');
Route::delete('/task/{task}', [RoomController::class, 'destroyTask'])->name('task.destroy');

Route::get('/room/{share_token}/messages', [RoomController::class, 'getMessages'])->name('message.index');
Route::post('/room/{share_token}/message', [RoomController::class, 'storeMessage'])->name('message.store');
});