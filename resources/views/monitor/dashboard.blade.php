<!DOCTYPE html>
<html lang="es" class="bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrativo - La Despensa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(51, 65, 85, 0.5);
        }
    </style>
</head>
<body class="text-slate-200 min-h-screen pb-12">

    <nav class="bg-slate-900/50 border-b border-slate-800 p-4 sticky top-0 z-50 backdrop-blur-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    </div>
                    <span class="text-xl font-black text-white tracking-tighter uppercase">La Despensa <span class="text-indigo-500 text-xs font-bold">ADMIN</span></span>
                </div>

                <div class="hidden md:flex gap-1 bg-slate-950/50 p-1 rounded-xl border border-slate-800">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg text-xs font-bold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-500 hover:text-white' }}">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('monitor.index') }}" class="px-4 py-2 rounded-lg text-xs font-bold transition {{ request()->routeIs('monitor.*') ? 'bg-cyan-600 text-white shadow-lg' : 'text-slate-500 hover:text-white' }}">
                        📡 Monitor
                    </a>
                    <a href="{{ route('reportes.index') }}" class="px-4 py-2 rounded-lg text-xs font-bold transition {{ request()->routeIs('reportes.*') ? 'bg-slate-800 text-white shadow-lg' : 'text-slate-500 hover:text-white' }}">
                        📁 Historial
                    </a>
                </div>
            </div>

            <div class="text-right">
                <p class="text-[10px] font-bold text-slate-500 uppercase">Estado del Sistema</p>
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-bold text-emerald-500">En Línea</span>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6">

        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-black tracking-tight text-white">Análisis de <span class="text-indigo-500">Operaciones</span></h1>
                <p class="text-slate-500 font-medium">Visualización de datos en tiempo real de sucursales.</p>
            </div>
            <div class="flex gap-3">
                <div class="bg-slate-900 border border-slate-800 px-4 py-2 rounded-2xl">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Total Hoy</p>
                    <p class="text-xl font-black text-emerald-500 leading-none mt-1">${{ number_format(array_sum($ventasData['valores']), 2) }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 glass-card p-8 rounded-[2rem] shadow-2xl">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                        Flujo de Ventas Semanal
                    </h3>
                </div>
                <div class="h-[350px]">
                    <canvas id="chartVentas"></canvas>
                </div>
            </div>

            <div class="glass-card p-8 rounded-[2rem] shadow-2xl flex flex-col">
                <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-8 text-center flex items-center justify-center gap-2">
                    <span class="w-2 h-2 bg-cyan-500 rounded-full"></span>
                    Participación
                </h3>
                <div class="flex-1 flex flex-col justify-center">
                    <div class="relative h-[250px]">
                        <canvas id="chartSucursales"></canvas>
                    </div>
                    <div class="mt-8 space-y-3">
                        @foreach($sucursalesLabels as $index => $label)
                        <div class="flex justify-between items-center text-xs font-bold p-2 rounded-lg bg-slate-800/30">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full" style="background-color: {{ ['#06b6d4', '#4f46e5', '#10b981', '#f59e0b'][$index % 4] }}"></span>
                                {{ $label }}
                            </span>
                            <span class="text-slate-400">${{ number_format($sucursalesValores[$index], 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        // Configuración Global
        Chart.defaults.color = '#64748b'; // slate-500
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.font.weight = '700';

        // 1. Gráfico de Barras con Degradado
        const ctxVentas = document.getElementById('chartVentas').getContext('2d');
        const gradientIndigo = ctxVentas.createLinearGradient(0, 0, 0, 400);
        gradientIndigo.addColorStop(0, '#6366f1');
        gradientIndigo.addColorStop(1, 'rgba(79, 70, 229, 0.1)');

        new Chart(ctxVentas, {
            type: 'bar',
            data: {
                labels: @json($ventasData['labels']),
                datasets: [{
                    label: 'Ventas ($)',
                    data: @json($ventasData['valores']),
                    backgroundColor: gradientIndigo,
                    borderColor: '#6366f1',
                    borderWidth: 2,
                    borderRadius: 12,
                    hoverBackgroundColor: '#818cf8',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 14 },
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(51, 65, 85, 0.3)', drawBorder: false },
                        ticks: { callback: value => '$' + value }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Gráfico de Dona
        new Chart(document.getElementById('chartSucursales'), {
            type: 'doughnut',
            data: {
                labels: @json($sucursalesLabels),
                datasets: [{
                    data: @json($sucursalesValores),
                    backgroundColor: ['#06b6d4', '#4f46e5', '#10b981', '#f59e0b'],
                    hoverOffset: 15,
                    borderWidth: 8,
                    borderColor: '#0f172a', // Espacio entre segmentos
                }]
            },
            options: {
                cutout: '75%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
</body>
</html>
