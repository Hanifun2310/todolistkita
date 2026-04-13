<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use App\Models\Task;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RoomController extends Controller
{
    // 1. Tampilkan Halaman Ruangan
    public function show(Request $request, $token)
    {
        $room = TodoList::where('share_token', $token)->firstOrFail();

        // Validasi Akses: Harus Owner atau terdaftar di pivot table
        $isMember = $room->owner_id === Auth::id() || $room->users()->where('user_id', Auth::id())->exists();
        if (!$isMember) {
            abort(403, 'Anda tidak memiliki akses ke ruangan ini.');
        }

        // Ambil filter bulan dari URL (default: bulan ini)
        $currentMonth = $request->get('month', date('Y-m'));

        // Ambil tugas khusus bulan yang dipilih
        $tasks = $room->tasks()->where('target_month', $currentMonth)->latest()->get();

        // Cari daftar bulan apa saja yang pernah ada tugasnya untuk Dropdown History
        $availableMonths = $room->tasks()->select('target_month')->distinct()->pluck('target_month')->toArray();
        if (!in_array(date('Y-m'), $availableMonths)) {
            $availableMonths[] = date('Y-m'); // Pastikan bulan ini selalu ada di pilihan
        }
        rsort($availableMonths); // Urutkan dari yang terbaru

        // Hitung jumlah hari (28/29/30/31) di bulan yang dipilih
        $daysInMonth = Carbon::createFromFormat('Y-m', $currentMonth)->daysInMonth;

        return view('dashboard.room', compact('room', 'tasks', 'currentMonth', 'availableMonths', 'daysInMonth'));
    }

    // --- API ENDPOINTS UNTUK JAVASCRIPT (TANPA REFRESH) ---

    // 2. Tambah Tugas Baru
    public function storeTask(Request $request, $token)
    {
        $room = TodoList::where('share_token', $token)->firstOrFail();
        
        $task = $room->tasks()->create([
            'title' => $request->title,
            'target_month' => $request->month ?? date('Y-m'),
            'completed_dates' => [] // Array kosong saat pertama dibuat
        ]);

        return response()->json($task);
    }

    // 3. Centang / Un-centang per Tanggal
    public function toggleTask(Request $request, Task $task)
    {
        $date = $request->date; // Tanggal yang diklik, misal: "2026-04-15"
        $dates = $task->completed_dates ?? [];

        // Jika tanggal sudah ada di array, hapus. Jika belum, tambahkan.
        if (($key = array_search($date, $dates)) !== false) {
            unset($dates[$key]); // Hapus centang
        } else {
            $dates[] = $date; // Tambah centang
        }

        // Simpan kembali ke database (menggunakan array_values agar index rapi)
        $task->update(['completed_dates' => array_values($dates)]);
        
        return response()->json($task);
    }
    
    // 4. Hapus Tugas
    public function destroyTask(Task $task)
    {
        $task->delete();
        return response()->json(['status' => 'success']);
    }

    // 5. Ambil Data Chat (Hanya 24 Jam Terakhir)
    public function getMessages($token)
    {
        $room = TodoList::where('share_token', $token)->firstOrFail();
        
        $messages = $room->messages()->with('user:id,name,avatar')
            ->where('created_at', '>=', now()->subDay()) // Hanya ambil 24 jam terakhir
            ->orderBy('created_at', 'asc')
            ->get();
            
        return response()->json($messages);
    }

    // 6. Kirim Pesan Chat
    public function storeMessage(Request $request, $token)
    {
        $room = TodoList::where('share_token', $token)->firstOrFail();
        
        $message = $room->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message
        ]);

        return response()->json($message->load('user:id,name,avatar'));
    }
}