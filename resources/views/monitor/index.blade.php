<!DOCTYPE html>
<html lang="es" class="bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Operativo de Pedidos - La Despensa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta http-equiv="refresh" content="60"> <link href="https://fonts.googleapis.com/css2?family=Inter:wght=400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-card {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(51, 65, 85, 0.5);
        }
        .status-pulse {
            animation: pulse-glow 2s infinite;
        }
        @keyframes pulse-glow {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 9999px; }
    </style>
</head>
<body class="text-slate-200 min-h-screen">

    <nav class="bg-slate-900/50 border-b border-slate-800 p-4 sticky top-0 z-50 backdrop-blur-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-cyan-600 rounded-lg flex items-center justify-center shadow-lg shadow-cyan-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <span class="text-xl font-black text-white tracking-tighter uppercase">La Despensa <span class="text-cyan-500 text-xs font-bold">MONITOR</span></span>
                </div>

                <div class="hidden md:flex gap-1 bg-slate-950/50 p-1 rounded-xl border border-slate-800">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg text-xs font-bold transition text-slate-500 hover:text-white">📊 Dashboard</a>
                    <a href="{{ route('monitor.index') }}" class="px-4 py-2 rounded-lg text-xs font-bold transition bg-cyan-600 text-white shadow-lg">📡 Monitor</a>
                    <a href="{{ route('reportes.index') }}" class="px-4 py-2 rounded-lg text-xs font-bold transition text-slate-500 hover:text-white">📁 Historial</a>
                </div>
            </div>

            <div class="text-right flex items-center gap-4">
                <div class="hidden sm:block">
                    <p class="text-[10px] font-black text-slate-500 uppercase">Sincronización</p>
                    <p class="text-xs font-bold text-slate-300">{{ now()->format('H:i:s') }}</p>
                </div>
                <span class="px-3 py-1 bg-amber-500/10 text-amber-500 text-[10px] font-black uppercase tracking-widest rounded-full border border-amber-500/20 status-pulse">COLA OPERATIVA</span>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6 uppercase">

        <div class="mb-8 border-b border-slate-800 pb-4 flex justify-between items-center">
            <h1 class="text-xs font-black tracking-widest text-slate-400">
                ⏳ TOTAL COMPROBANTES PENDIENTES: <span class="text-amber-500 font-black text-sm">{{ $pedidosPorFacturador->sum(fn($p) => $p->count()) }}</span>
            </h1>
        </div>

        {{-- FILTRO RÁPIDO INTERNO DE NAVEGACIÓN --}}
        @if($pedidosPorFacturador->count() > 0)
            <div class="flex gap-2 mb-8 overflow-x-auto pb-2 no-scrollbar">
                <span class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-[10px] font-black shadow-lg">CAJAS ACTIVAS:</span>
                @foreach($pedidosPorFacturador->keys() as $nombre)
                    <a href="#{{ Str::slug($nombre) }}" class="bg-slate-900 border border-slate-800 text-slate-400 px-5 py-2 rounded-xl text-[10px] font-black hover:border-indigo-500 transition-all">
                        {{ $nombre }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- BUCLE DE RENDERIZADO POR FACTURADOR --}}
        @forelse($pedidosPorFacturador as $facturador => $pedidos)
            <div id="{{ Str::slug($facturador) }}" class="mb-12 scroll-mt-24">
                <div class="flex items-center gap-4 mb-6">
                    <h2 class="text-xl font-black text-white flex items-center gap-3">
                        <span class="w-1.5 h-6 bg-amber-500 rounded-full"></span>
                        FACTURADOR: <span class="text-amber-500">{{ $facturador }}</span>
                    </h2>
                    <span class="px-3 py-1 bg-slate-900 border border-slate-800 rounded-lg text-[10px] font-black text-slate-500 italic">
                        {{ $pedidos->count() }} PENDIENTES
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($pedidos as $pedido)
                        <div class="glass-card rounded-[2rem] p-6 border-l-4 border-l-amber-500 hover:shadow-2xl transition-all group">
                            
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-[10px] font-black text-slate-500 tracking-widest leading-none mb-1">CÓDIGO PEDIDO</p>
                                    <h3 class="text-2xl font-black text-white tracking-tighter group-hover:text-amber-400 transition-colors">
                                        {{ $pedido->pedidos_codigo }}
                                    </h3>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-black text-cyan-500 tracking-tighter">{{ $pedido->sucursal }}</p>
                                    <p class="text-[9px] font-bold text-slate-600">{{ $pedido->equipo }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between bg-slate-950/50 rounded-xl px-4 py-2 mb-4 border border-slate-800">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                    <span class="text-[10px] font-black text-amber-500">PENDIENTE CONTABLE</span>
                                </div>
                                <span class="text-[9px] font-bold text-slate-500 italic">
                                    {{ \Carbon\Carbon::parse($pedido->fecha_emision)->locale('es')->diffForHumans() }}
                                </span>
                            </div>

                            <h4 class="text-sm font-black text-slate-200 mb-1 truncate">{{ $pedido->cliente }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 mb-2">IDENTIFICACIÓN: <span class="text-slate-300">{{ $pedido->identificacion }}</span></p>
                            <p class="text-3xl font-black text-emerald-400 mb-6 tracking-tighter">${{ number_format($pedido->total, 2) }}</p>

                            <div class="space-y-2">
                                <p class="text-[9px] font-black text-slate-500 tracking-widest border-b border-slate-800 pb-1">DESGLOSE DE PRODUCTOS</p>
                                <div class="max-h-48 overflow-y-auto pr-2 custom-scroll">
                                    <table class="w-full text-[11px]">
                                        <tbody class="divide-y divide-slate-800/30">
                                            @php $items = json_decode($pedido->detalles, true); @endphp
                                            @if($items)
                                                @foreach($items as $item)
                                                    <tr class="group/item">
                                                        <td class="py-2 font-black text-cyan-500 w-8">{{ number_format($item['cantidad'], 0) }}</td>
                                                        <td class="py-2 font-medium text-slate-400">
                                                            {{ $item['descripcion'] }}
                                                            <span class="block text-[8px] text-slate-600 font-bold uppercase tracking-tighter">{{ $item['codigo'] }}</span>
                                                        </td>
                                                        <td class="py-2 text-right font-bold text-slate-300 italic">${{ number_format($item['precioiva'], 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-32 opacity-20">
                <svg class="w-24 h-24 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-2xl font-black tracking-widest uppercase">Sin pedidos pendientes en las sucursales</p>
            </div>
        @endforelse
    </main>

    <script>
        console.log("Monitor Operativo de Contingencia. Ejecución limpia.");
    </script>
</body>
</html>