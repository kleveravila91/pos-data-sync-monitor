<!DOCTYPE html>
<html lang="es" class="bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Pedidos - La Despensa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(51, 65, 85, 0.4);
        }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>
<body class="text-slate-200 min-h-screen pb-12">

    <nav class="bg-slate-900/50 border-b border-slate-800 p-4 sticky top-0 z-50 backdrop-blur-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <span class="text-xl font-black text-white tracking-tighter uppercase">La Despensa <span class="text-indigo-500 text-xs font-bold">ADMIN</span></span>
                </div>
                <div class="hidden md:flex gap-1 bg-slate-950/50 p-1 rounded-xl border border-slate-800">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg text-xs font-bold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-500 hover:text-white' }}">📊 Dashboard</a>
                    <a href="{{ route('monitor.index') }}" class="px-4 py-2 rounded-lg text-xs font-bold transition {{ request()->routeIs('monitor.*') ? 'bg-cyan-600 text-white shadow-lg' : 'text-slate-500 hover:text-white' }}">📡 Monitor</a>
                    <a href="{{ route('reportes.index') }}" class="px-4 py-2 rounded-lg text-xs font-bold transition {{ request()->routeIs('reportes.*') ? 'bg-slate-700 text-white shadow-lg' : 'text-slate-500 hover:text-white' }}">📁 Historial</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 mt-8">
        {{-- FILTROS --}}
        <div class="glass-card rounded-[2rem] overflow-hidden mb-8 shadow-2xl">
            <div class="bg-slate-900/80 border-b border-slate-800 px-8 py-4">
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Filtros de Auditoría</h3>
            </div>
            <form action="{{ route('reportes.index') }}" method="GET" class="p-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">📍 Sucursal</label>
                    <select name="sucursal" class="w-full bg-slate-950 border-slate-700 text-slate-300 rounded-xl text-sm p-2.5">
                        <option value="">Todas las sucursales</option>
                        @foreach($sucursales as $s)
                            <option value="{{ $s }}" {{ request('sucursal') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">👤 Facturador</label>
                    <select name="facturador" class="w-full bg-slate-950 border-slate-700 text-slate-300 rounded-xl text-sm p-2.5">
                        <option value="">Todos los usuarios</option>
                        @foreach($facturadores as $f)
                            <option value="{{ $f }}" {{ request('facturador') == $f ? 'selected' : '' }}>{{ $f }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2 md:col-span-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">📅 Rango de Fechas</label>
                    <div class="flex items-center gap-3">
                        <input type="date" name="desde" value="{{ request('desde') }}" class="flex-1 bg-slate-950 border-slate-700 text-slate-300 rounded-xl text-sm p-2">
                        <span class="text-slate-600 font-bold text-xs uppercase italic">A</span>
                        <input type="date" name="hasta" value="{{ request('hasta') }}" class="flex-1 bg-slate-950 border-slate-700 text-slate-300 rounded-xl text-sm p-2">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-2.5 rounded-xl font-black text-xs uppercase shadow-lg shadow-indigo-900/40 transition-all active:scale-95">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- LISTADO --}}
        <div class="glass-card rounded-[2rem] overflow-hidden shadow-2xl border border-slate-800">
            <div class="px-8 py-5 bg-slate-900/80 border-b border-slate-800 flex justify-between items-center">
                <div>
                    <h3 class="font-black text-white text-lg tracking-tight">Listado de Pedidos</h3>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">Sincronización Perseo-PC</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="window.location.href='{{ route('reportes.exportar', array_merge(request()->all(), ['tipo' => 'excel'])) }}'" class="bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-tighter hover:bg-emerald-500 hover:text-white transition-all">📊 Excel</button>
                    <button onclick="window.location.href='{{ route('reportes.exportar', array_merge(request()->all(), ['tipo' => 'pdf'])) }}'" class="bg-rose-500/10 text-rose-500 border border-rose-500/20 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-tighter hover:bg-rose-500 hover:text-white transition-all">📄 PDF</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-950/50 border-b border-slate-800">
                            <th class="px-8 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Fecha / Hora</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Código</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Cliente</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Origen</th>
                            <th class="px-8 py-4 text-right text-[10px] font-black text-slate-500 uppercase tracking-widest">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50">
                        @foreach($pedidos as $pedido)
                        <tr onclick="abrirModal({{ $pedido->id }})" class="hover:bg-indigo-600/10 cursor-pointer transition-colors group">
                            <td class="px-8 py-4 text-sm font-medium text-slate-400 group-hover:text-slate-200">
                                {{ \Carbon\Carbon::parse($pedido->fecha_emision)->format('d/m/Y') }} <br>
                                <span class="text-[10px] text-slate-600 font-bold uppercase">{{ \Carbon\Carbon::parse($pedido->fecha_emision)->format('H:i') }}</span>
                            </td>
                            <td class="px-8 py-4 text-sm">
                                <span class="text-indigo-400 font-black tracking-tight">{{ $pedido->pedidos_codigo }}</span> <br>
                                <span class="text-[10px] text-slate-600 font-bold uppercase">ID: #{{ $pedido->id_local_pedido }}</span>
                            </td>
                            <td class="px-8 py-4"><p class="text-sm font-bold text-slate-200">{{ $pedido->cliente }}</p></td>
                            <td class="px-8 py-4">
                                <p class="text-xs font-black text-cyan-500 uppercase tracking-tighter">{{ $pedido->sucursal }}</p>
                                <p class="text-[10px] text-slate-600 font-bold italic">{{ $pedido->equipo }}</p>
                            </td>
                            <td class="px-8 py-4 text-right font-black text-emerald-500 text-lg">${{ number_format($pedido->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-8 py-6 bg-slate-900/50 border-t border-slate-800">
				{{ $pedidos->appends(request()->query())->links() }}
			</div>
        </div>
    </main>

    {{-- MODAL ADAPTADA --}}
    <div id="modalDetalle" class="fixed inset-0 z-[100] hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div onclick="cerrarModal()" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity"></div>
            <div class="relative bg-slate-900 border border-slate-700 rounded-[2.5rem] overflow-hidden shadow-2xl max-w-4xl w-full">
                <div class="bg-slate-800/50 px-10 py-6 border-b border-slate-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-black text-white tracking-tighter">DETALLE DEL PEDIDO</h3>
                        <p class="text-[10px] text-slate-500 font-black uppercase tracking-[0.2em] mt-1">Sincronización Auditoría</p>
                    </div>
                    <button onclick="cerrarModal()" class="p-2 hover:bg-slate-700 rounded-full text-slate-400 hover:text-white transition">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="px-10 py-8" id="contenidoModal"></div>
                <div class="px-10 py-6 bg-slate-950/50 flex justify-end">
                    <button onclick="cerrarModal()" class="bg-slate-700 hover:bg-slate-600 text-white px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    const cacheDetalles = {};
    function abrirModal(id) {
        const modal = document.getElementById('modalDetalle');
        const contenido = document.getElementById('contenidoModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        if (cacheDetalles[id]) {
            contenido.innerHTML = cacheDetalles[id];
            return;
        }

        contenido.innerHTML = `<div class="flex flex-col items-center py-20"><div class="animate-spin h-10 w-10 border-4 border-indigo-500 border-t-transparent rounded-full mb-4"></div><p class="text-slate-500 font-black text-xs uppercase tracking-widest">Consultando base de datos...</p></div>`;

        fetch(`{{ url('/reportes/detalle') }}/${id}`, { headers: { 'Accept': 'application/json' } })
        .then(response => response.json())
        .then(data => {
            // Conversión segura de detalles JSON
            let productos = typeof data.detalles === 'string' ? JSON.parse(data.detalles) : data.detalles;
            
            let html = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    <div class="bg-slate-950/50 p-6 rounded-3xl border border-slate-800">
                        <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest mb-1">Folio Auditoría</p>
                        <p class="text-indigo-400 font-black text-xl tracking-tighter">${data.pedidos_codigo}</p>
                    </div>
                    <div class="bg-slate-950/50 p-6 rounded-3xl border border-slate-800">
                        <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest mb-1">Punto de Venta</p>
                        <p class="text-white font-black text-xl tracking-tighter uppercase">${data.sucursal}</p>
                    </div>
                    <div class="bg-slate-950/50 p-6 rounded-3xl border border-slate-800">
                        <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest mb-1">Total Liquidado</p>
                        <p class="text-emerald-500 font-black text-2xl tracking-tighter">$${Number(data.total).toFixed(2)}</p>
                    </div>
                </div>
                <div class="rounded-3xl border border-slate-800 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-800/50 text-slate-500">
								<th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest">Codigo</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest">Descripción Producto</th>
                                <th class="px-6 py-4 text-center text-[10px] font-black uppercase tracking-widest">Cant.</th>
                                <th class="px-6 py-4 text-right text-[10px] font-black uppercase tracking-widest">P. Unit</th>
                                <th class="px-6 py-4 text-right text-[10px] font-black uppercase tracking-widest">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
            `;
            productos.forEach(item => {
                const cant = Number(item.cantidad) || 0;
                const prec = Number(item.precio) || 0;
                html += `
                    <tr class="hover:bg-slate-800/20 transition-colors">
						<td class="px-6 py-4 text-slate-200 font-bold">${item.codigo}</td>
                        <td class="px-6 py-4 text-slate-200 font-bold">${item.descripcion || 'S/N'}</td>
                        <td class="px-6 py-4 text-center text-slate-400 font-black">${cant}</td>
                        <td class="px-6 py-4 text-right text-slate-400 font-medium">$${prec.toFixed(2)}</td>
                        <td class="px-6 py-4 text-right text-white font-black">$${(cant * prec).toFixed(2)}</td>
                    </tr>
                `;
            });
            html += `</tbody></table></div>`;
            cacheDetalles[id] = html;
            contenido.innerHTML = html;
        });
    }

    function cerrarModal() {
        document.getElementById('modalDetalle').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    </script>
</body>
</html>