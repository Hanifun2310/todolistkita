<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $room->title }} - SyncDo</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        :root { --font-primary: "Plus Jakarta Sans", sans-serif; }
        body {
            font-family: var(--font-primary);
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 1.5px, transparent 1.5px);
            background-size: 28px 28px;
        }
        .dashboard-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid rgba(0, 0, 0, 0.03);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02); 
        }
        
        /* Custom Scrollbar untuk Chat & Kolom Tanggal */
        ::-webkit-scrollbar { height: 6px; width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
        
        .custom-scrollbar::-webkit-scrollbar { height: 4px; }
    </style>
</head>
<body class="text-slate-800 antialiased h-screen flex flex-col overflow-hidden">

    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 z-40 shrink-0">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                        <i class="ph-bold ph-arrow-left"></i>
                    </a>
                    <span class="font-bold text-lg flex items-center gap-2">
                        <i class="ph-fill ph-kanban text-indigo-500"></i> {{ $room->title }}
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow p-4 md:p-6 lg:p-8 flex flex-col lg:flex-row gap-6 overflow-hidden">
        
        <div class="w-full lg:w-1/4 dashboard-card flex flex-col h-1/2 lg:h-full bg-white relative">
            <div class="p-4 border-b border-slate-100 shrink-0">
                <h3 class="font-bold text-md mb-3 flex items-center gap-2 text-slate-700">
                    <i class="ph-duotone ph-calendar-check text-indigo-500"></i> Target Bulanan
                </h3>
                
                <form action="{{ route('room.show', $room->share_token) }}" method="GET" class="mb-4">
                    <select name="month" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-slate-600 cursor-pointer">
                        @foreach($availableMonths as $month)
                            <option value="{{ $month }}" {{ $currentMonth == $month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <form id="task-form" class="flex gap-2">
                    <input type="text" id="task-input" required placeholder="Tugas baru..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 transition-colors">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg transition-colors">
                        <i class="ph-bold ph-plus"></i>
                    </button>
                </form>
            </div>

            <div class="p-4 overflow-y-auto flex-grow bg-slate-50/30" id="task-list">
                @forelse($tasks as $task)
                    <div class="mb-4 bg-white border border-slate-200 rounded-xl p-3 relative group shadow-sm hover:border-indigo-200 transition-colors" id="task-{{ $task->id }}">
                        
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-bold text-sm text-slate-800">{{ $task->title }}</span>
                            <button onclick="deleteTask({{ $task->id }})" class="text-slate-300 hover:text-rose-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="ph-bold ph-trash"></i>
                            </button>
                        </div>
                        
                        <div class="flex gap-2 overflow-x-auto pb-2 custom-scrollbar">
                            @for($i = 1; $i <= $daysInMonth; $i++)
                                @php
                                    // Membuat format YYYY-MM-DD
                                    $dateString = $currentMonth . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                                    // Cek apakah tanggal ini ada di dalam array completed_dates
                                    $isChecked = in_array($dateString, $task->completed_dates ?? []);
                                @endphp
                                
                                <button onclick="toggleDate({{ $task->id }}, '{{ $dateString }}', this)" 
                                        class="w-8 h-8 flex-shrink-0 rounded-lg text-xs font-bold transition-all border {{ $isChecked ? 'bg-indigo-600 text-white border-indigo-600 shadow-md scale-105' : 'bg-slate-50 text-slate-500 border-slate-200 hover:bg-indigo-50' }}">
                                    {{ $i }}
                                </button>
                            @endfor
                        </div>

                    </div>
                @empty
                    <div class="text-center text-slate-400 text-sm mt-10">Belum ada tugas di bulan ini.</div>
                @endforelse
            </div>
        </div>

        <div class="w-full lg:w-3/4 dashboard-card flex flex-col h-1/2 lg:h-full bg-slate-50/50 relative overflow-hidden">
            <div class="p-4 bg-white border-b border-slate-100 shrink-0 flex justify-between items-center z-10 shadow-sm">
                <div class="flex items-center gap-2">
                    <i class="ph-duotone ph-chats-circle text-2xl text-emerald-500"></i>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Diskusi Ruangan</h3>
                        <p class="text-xs text-slate-400 hidden sm:block">Pesan otomatis terhapus dalam 24 jam untuk menjaga performa.</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100 flex items-center gap-1">
                    <i class="ph-bold ph-users"></i> {{ $room->users->count() + 1 }} Member
                </span>
            </div>

            <div id="chat-box" class="flex-grow p-4 sm:p-6 overflow-y-auto space-y-4">
                </div>

            <div class="p-4 bg-white border-t border-slate-100 shrink-0">
                <form id="chat-form" class="relative flex items-center">
                    <input type="text" id="chat-input" required placeholder="Ketik pesan..." autocomplete="off" class="w-full bg-slate-100 border-none rounded-xl pl-4 pr-14 py-3 sm:py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button type="submit" class="absolute right-2 w-10 h-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg flex items-center justify-center transition-colors shadow-md">
                        <i class="ph-fill ph-paper-plane-right"></i>
                    </button>
                </form>
            </div>
        </div>

    </main>

    <script>
        const token = "{{ $room->share_token }}";
        const currentMonth = "{{ $currentMonth }}";
        const currentUserId = {{ auth()->id() }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // === 1. LOGIKA TASK & TANGGAL ===

        // Menambah Tugas Baru
        document.getElementById('task-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('task-input');
            const title = input.value;
            if(!title) return;

            fetch(`/room/${token}/task`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ title: title, month: currentMonth })
            })
            .then(res => res.json())
            .then(data => {
                input.value = '';
                location.reload(); // Reload ringan untuk merender ulang kalender bulan ini
            });
        });

        // Menghapus Tugas
        function deleteTask(taskId) {
            if(!confirm('Hapus tugas ini beserta riwayatnya?')) return;
            
            fetch(`/task/${taskId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            }).then(() => {
                document.getElementById(`task-${taskId}`).remove();
            });
        }

        // Toggle Centang Tanggal
        function toggleDate(taskId, dateStr, btnElement) {
            fetch(`/task/${taskId}`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ date: dateStr })
            }).then(() => {
                // Manipulasi class visual tombol (Animasi Instan)
                if (btnElement.classList.contains('bg-indigo-600')) {
                    // Batalkan Centang
                    btnElement.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-md', 'scale-105');
                    btnElement.classList.add('bg-slate-50', 'text-slate-500', 'border-slate-200');
                } else {
                    // Beri Centang
                    btnElement.classList.remove('bg-slate-50', 'text-slate-500', 'border-slate-200');
                    btnElement.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-md', 'scale-105');
                }
            });
        }

        // === 2. LOGIKA CHAT POLLING ===

        const chatBox = document.getElementById('chat-box');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        let lastMessageCount = 0;

        function loadMessages() {
            fetch(`/room/${token}/messages`)
            .then(res => res.json())
            .then(data => {
                if(data.length !== lastMessageCount) {
                    chatBox.innerHTML = '';
                    data.forEach(msg => {
                        const isMe = msg.user_id === currentUserId;
                        const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                        const avatarUrl = msg.user.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(msg.user.name)}&background=random`;
                        
                        // Render Bubble Chat
                        const chatHTML = `
                            <div class="flex ${isMe ? 'justify-end' : 'justify-start'} w-full animate-fade-down" style="animation-duration: 0.3s">
                                <div class="flex gap-3 max-w-[85%] sm:max-w-[70%] ${isMe ? 'flex-row-reverse' : 'flex-row'}">
                                    <img src="${avatarUrl}" class="w-8 h-8 rounded-full border border-slate-200 shrink-0">
                                    <div class="flex flex-col ${isMe ? 'items-end' : 'items-start'}">
                                        <span class="text-[10px] sm:text-xs text-slate-400 mb-1 font-medium">${msg.user.name} • ${time}</span>
                                        <div class="px-4 py-2 sm:py-2.5 rounded-2xl text-xs sm:text-sm shadow-sm ${isMe ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white border border-slate-100 text-slate-700 rounded-tl-none'}">
                                            ${msg.message}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        chatBox.insertAdjacentHTML('beforeend', chatHTML);
                    });
                    chatBox.scrollTop = chatBox.scrollHeight; // Auto scroll ke bawah jika ada pesan baru
                    lastMessageCount = data.length;
                }
            });
        }

        // Mengirim Chat
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const msg = chatInput.value;
            if(!msg) return;

            chatInput.value = ''; // Segera kosongkan input

            fetch(`/room/${token}/message`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ message: msg })
            }).then(() => loadMessages()); // Muat ulang layar chat
        });

        // Jalankan saat pertama dimuat, lalu cek pesan baru setiap 3 detik
        loadMessages();
        setInterval(loadMessages, 3000); 

    </script>
</body>
</html>