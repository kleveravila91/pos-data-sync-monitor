<?php

namespace App\Http\Controllers;

use App\Models\MonitorPedido;
use App\Exports\PedidosExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf; // O 'use PDF;' si configuraste el alias
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $sucursales = MonitorPedido::select('sucursal')->distinct()->pluck('sucursal');
        $facturadores = MonitorPedido::select('facturador')->distinct()->pluck('facturador');
        $pedidos = MonitorPedido::query()
            ->when($request->sucursal, fn($q) => $q->where('sucursal', $request->sucursal))
            ->when($request->facturador, fn($q) => $q->where('facturador', $request->facturador))
            ->when($request->desde, fn($q) => $q->whereDate('fecha_emision', '>=', $request->desde))
            ->when($request->hasta, fn($q) => $q->whereDate('fecha_emision', '<=', $request->hasta))
            ->orderBy('fecha_emision', 'desc')
            ->paginate(25); // Paginación para no saturar la vista

        return view('reportes.index', compact('pedidos', 'sucursales', 'facturadores'));
    }

    public function exportar(Request $request)
    {
        $query = MonitorPedido::query();

        // Filtros de texto
        if ($request->sucursal) $query->where('sucursal', $request->sucursal);
        if ($request->facturador) $query->where('facturador', $request->facturador);

        // Filtros de fecha (Soporta ambos nombres por seguridad)
        $inicio = $request->fecha_inicio ?? $request->desde;
        $fin = $request->fecha_fin ?? $request->hasta;

        if ($inicio && $fin) {
            $query->whereBetween('fecha_emision', [$inicio, $fin]);
        }

        $pedidos = $query->orderBy('fecha_emision', 'desc')->get();

        // Lógica de descarga
        if ($request->tipo == 'pdf') {
            $pdf = Pdf::loadView('reportes.pdf', compact('pedidos'))
                    ->setPaper('a4', 'landscape');
            return $pdf->download('reporte_pedidos.pdf');
        }

        // Si no es PDF, descargamos Excel
        return Excel::download(
            new PedidosExport($pedidos),
            'reporte_pedidos.xlsx'
        );
    }

    public function getDetalle($id) {
		try {
			$pedido = MonitorPedido::find($id);
			// BORRA EL dd($pedido) DE AQUÍ

			if (!$pedido) {
				return response()->json(['error' => 'No encontrado'], 404);
			}

			return response()->json([
				'pedidos_codigo' => $pedido->pedidos_codigo,
				'sucursal' => $pedido->sucursal,
				'total' => $pedido->total,
				'detalles' => $pedido->detalles 
			]);
		} catch (\Exception $e) {
			return response()->json(['error' => $e.getMessage()], 500);
		}
	}
}
