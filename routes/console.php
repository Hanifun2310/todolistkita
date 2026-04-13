<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Message; // Jangan lupa import model Message
use Illuminate\Support\Facades\Log;

// (Command bawaan Laravel, biarkan saja)
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// --- SISTEM PEMBERSIH CHAT OTOMATIS ---
Schedule::call(function () {
    // Cari pesan yang tanggal buatnya lebih lama dari 1 hari (24 jam) yang lalu, lalu hapus.
    $deletedCount = Message::where('created_at', '<', now()->subDay())->delete();
    
    // Opsional: Mencatat aktivitas ke file log Laravel agar Anda bisa memantau
    if ($deletedCount > 0) {
        Log::info("Sistem Auto-Clean: {$deletedCount} pesan lama berhasil dihapus.");
    }
})->hourly(); // Jalankan pengecekan ini setiap 1 jam sekali