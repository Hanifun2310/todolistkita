<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ToDo List Kolaborasi</title>
    
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        :root {
            --font-primary: "Plus Jakarta Sans", sans-serif;
            --color-primary: #4f46e5;
            --ease-spring: cubic-bezier(0.175, 0.885, 0.32, 1.1);
        }
        
        body {
            font-family: var(--font-primary);
            /* Background dotted pattern khas portofolio Anda */
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 1.5px, transparent 1.5px);
            background-size: 28px 28px;
        }

        /* Dashboard Card Style */
        .dashboard-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 32px;
            border: 1px solid rgba(0, 0, 0, 0.03);
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.02),
                0 2px 4px -1px rgba(0, 0, 0, 0.02); 
            transition: all 0.4s var(--ease-spring);
        }
        
        .dashboard-card:hover {
            transform: translateY(-6px);
            box-shadow: 
                0 20px 25px -5px rgba(0, 0, 0, 0.05),
                0 8px 10px -6px rgba(0, 0, 0, 0.01),
                0 0 0 1px rgba(79, 70, 229, 0.1); 
        }

        /* Animation */
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-down { 
            animation: fadeDown 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
        }

        /* Tombol Custom (Btn Secondary + Hover Primary) */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background-color: white;
            color: #475569;
            border: 1px solid #e2e8f0;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
        }
        .btn-google:hover {
            border-color: #4f46e5;
            background-color: #eef2ff;
            color: #4f46e5;
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
        }
    </style>
</head>
<body class="text-slate-800 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="dashboard-card w-full max-w-md text-center animate-fade-down relative overflow-hidden group">
        
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl transition-transform duration-700 group-hover:scale-150"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl transition-transform duration-700 group-hover:scale-150"></div>

        <div class="relative z-10">
            <div class="mx-auto w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 shadow-inner border border-indigo-100 transform transition-transform duration-500 group-hover:rotate-12">
                <i class="ph-fill ph-check-square-offset text-3xl"></i>
            </div>

            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Sync<span class="text-indigo-600">Do.</span></h2>
            <p class="text-slate-500 mb-8 font-medium text-sm md:text-base">Kolaborasi ToDo List dalam satu ruang yang sama.</p>

            @if (session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-xl mb-6 text-sm font-medium flex items-center gap-2 text-left">
                    <i class="ph-fill ph-warning-circle text-lg"></i>
                    {{ session('error') }}
                </div>
            @endif

            <a href="{{ route('google.login') }}" class="btn-google">
                <svg class="h-5 w-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </a>

            <div class="mt-8 text-xs text-slate-400 font-medium">
                Dibuat dengan <i class="ph-fill ph-heart text-rose-500 mx-1 animate-pulse"></i> untuk Kolaborasi
            </div>
        </div>
    </div>

</body>
</html>