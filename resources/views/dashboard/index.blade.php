<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SyncDo</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        :root {
            --font-primary: "Plus Jakarta Sans", sans-serif;
            --ease-spring: cubic-bezier(0.175, 0.885, 0.32, 1.1);
        }
        
        body {
            font-family: var(--font-primary);
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 1.5px, transparent 1.5px);
            background-size: 28px 28px;
        }

        .dashboard-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid rgba(0, 0, 0, 0.03);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02); 
            transition: all 0.4s var(--ease-spring);
        }
        
        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(79, 70, 229, 0.1); 
        }

        .animate-fade-down { animation: fadeDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="text-slate-800 antialiased min-h-screen pb-12">

    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40 animate-fade-down">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white">
                        <i class="ph-bold ph-check-square-offset text-xl"></i>
                    </div>
                    <span class="font-bold text-xl tracking-tight">Sync<span class="text-indigo-600">Do.</span></span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 bg-slate-50 px-3 py-1.5 rounded-full border border-slate-100">
                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}" class="w-8 h-8 rounded-full">
                        <span class="text-sm font-semibold hidden sm:block">{{ auth()->user()->name }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-rose-50 hover:text-rose-600 transition-colors">
                            <i class="ph-bold ph-sign-out text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        
        @if (session('success'))
            <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl mb-6 flex gap-2 items-center text-sm font-bold border border-emerald-100 animate-fade-down">
                <i class="ph-fill ph-check-circle text-lg"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-rose-50 text-rose-600 p-4 rounded-xl mb-6 flex gap-2 items-center text-sm font-bold border border-rose-100 animate-fade-down">
                <i class="ph-fill ph-warning-circle text-lg"></i> {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
            
            <div class="md:col-span-1 space-y-6 animate-fade-down">
                <div class="dashboard-card border-t-4 border-t-indigo-500">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2"><i class="ph-duotone ph-plus-circle text-indigo-500 text-xl"></i> Buat Ruang Baru</h3>
                    <form action="{{ route('todo.store') }}" method="POST">
                        @csrf
                        <input type="text" name="title" required placeholder="Contoh: Project Jajan Yuk" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mb-3">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl text-sm transition-colors shadow-lg shadow-indigo-200">
                            Buat ToDo List
                        </button>
                    </form>
                </div>

                <div class="dashboard-card">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2"><i class="ph-duotone ph-link text-emerald-500 text-xl"></i> Gabung Ruang</h3>
                    <form action="{{ route('todo.join') }}" method="POST">
                        @csrf
                        <input type="text" name="share_token" required placeholder="Masukkan Token / Kode Invite" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 mb-3">
                        <button type="submit" class="w-full bg-white border border-slate-200 hover:border-emerald-500 hover:text-emerald-600 text-slate-600 font-bold py-3 rounded-xl text-sm transition-colors">
                            Gabung Sekarang
                        </button>
                    </form>
                </div>
            </div>

            <div class="md:col-span-2 animate-fade-down" style="animation-delay: 0.1s;">
                <h2 class="text-2xl font-extrabold mb-6 flex items-center gap-3">
                    Ruang Kolaborasi Anda
                    <span class="bg-indigo-100 text-indigo-700 text-xs py-1 px-2.5 rounded-full">{{ $allLists->count() }}</span>
                </h2>

                @if($allLists->isEmpty())
                    <div class="dashboard-card text-center py-12 border-dashed border-2">
                        <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="ph-duotone ph-folder-dashed text-3xl"></i>
                        </div>
                        <h4 class="text-slate-500 font-bold mb-1">Belum ada ToDo List</h4>
                        <p class="text-sm text-slate-400">Buat ruang baru atau gabung menggunakan kode invite.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($allLists as $list)
                            <div class="dashboard-card group relative overflow-hidden flex flex-col h-full cursor-pointer hover:border-indigo-200">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <i class="ph-fill ph-kanban text-lg"></i>
                                    </div>
                                    
                                    @if($list->owner_id === auth()->id())
                                        <form action="{{ route('todo.destroy', $list->id) }}" method="POST" onsubmit="return confirm('Hapus ToDo List ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors p-1" title="Hapus Ruang">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                
                                <h4 class="text-lg font-bold text-slate-800 mb-1">{{ $list->title }}</h4>
                                <p class="text-xs text-slate-500 mb-6 flex-grow">
                                    {{ $list->owner_id === auth()->id() ? 'Anda sebagai Pemilik' : 'Pemilik: ' . $list->owner->name }}
                                </p>
                                
                                <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                                    <div class="text-xs font-semibold px-2 py-1 bg-slate-100 text-slate-600 rounded-md flex items-center gap-1 cursor-text" title="Token Invite">
                                        <i class="ph-bold ph-key"></i> {{ $list->share_token }}
                                    </div>
                                    <button class="text-indigo-600 font-bold text-sm flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                        <a href="{{ route('room.show', $list->share_token) }}" class="text-indigo-600 font-bold text-sm flex items-center gap-1 group-hover:translate-x-1 transition-transform"> Buka <i class="ph-bold ph-arrow-right"></i> </a>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </main>

</body>
</html>