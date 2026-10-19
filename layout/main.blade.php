<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard CRM' }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('scripts')

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .container-expanded {
            max-width: 1400px;
        }
    </style>
</head>
<body class="bg-gray-50">
    
    {{-- Conditionally render Nav component --}}
    @if(view()->exists('components.dashboard.nav'))
        <x-dashboard.nav />
    @elseif(view()->exists('components.dashboard.Nav'))
        <x-dashboard.Nav />
    @endif

    <main class="w-full">
        @yield('content')
    </main>

    {{-- Conditionally render Toast component --}}
    @if(view()->exists('components.dashboard.toast'))
        <x-dashboard.toast />
    @elseif(view()->exists('components.dashboard.Toast'))
        <x-dashboard.Toast />
    @endif

    <script src="{{ asset('js/geo-chart.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak',
            text: "{{ session('error') }}",
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'OK',
            timer: 3000,
            timerProgressBar: true
        });
    </script>
    @endif
</body>
</html>