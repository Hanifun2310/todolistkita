<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // 1. Menampilkan Halaman Utama
    public function index()
    {
        $user = Auth::user();
        
        // Mengambil list yang DIBUAT oleh user
        $ownedLists = $user->ownedTodoLists;
        
        // Mengambil list yang DIIKUTI oleh user (sebagai kolaborator)
        $joinedLists = $user->todoLists;
        
        // Menggabungkan keduanya agar tampil di satu halaman
        $allLists = $ownedLists->merge($joinedLists)->sortByDesc('created_at');

        return view('dashboard.index', compact('allLists'));
    }

    // 2. Membuat ToDo List Baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        TodoList::create([
            'owner_id' => Auth::id(),
            'title' => $request->title,
        ]);

        return back()->with('success', 'ToDo List baru berhasil dibuat!');
    }

    // 3. Bergabung ke ToDo List (Join via Token)
    public function join(Request $request)
    {
        $request->validate([
            'share_token' => 'required|string',
        ]);

        $todoList = TodoList::where('share_token', $request->share_token)->first();

        if (!$todoList) {
            return back()->with('error', 'Token ToDo List tidak ditemukan.');
        }

        // Cek jika user adalah pemiliknya
        if ($todoList->owner_id === Auth::id()) {
            return back()->with('error', 'Anda adalah pembuat ToDo List ini.');
        }

        // Cek jika user sudah bergabung
        if ($todoList->users()->where('user_id', Auth::id())->exists()) {
            return back()->with('info', 'Anda sudah berada di dalam ToDo List ini.');
        }

        // Cek jika ruangan sudah penuh (Maksimal 2 orang: 1 Owner + 1 Kolaborator)
        if ($todoList->users()->count() >= 1) {
            return back()->with('error', 'ToDo List ini sudah penuh (Maks. 2 orang).');
        }

        // Masukkan user ke tabel pivot
        $todoList->users()->attach(Auth::id());

        return back()->with('success', 'Berhasil bergabung ke ToDo List kolaborasi!');
    }

    // 4. Menghapus ToDo List
    public function destroy($id)
    {
        $todoList = TodoList::findOrFail($id);

        // Hanya Owner yang boleh menghapus
        if ($todoList->owner_id !== Auth::id()) {
            return back()->with('error', 'Hanya pembuat yang dapat menghapus ToDo List ini.');
        }

        $todoList->delete(); // Ini otomatis menghapus task & message karena Cascade

        return back()->with('success', 'ToDo List berhasil dihapus.');
    }
}