<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PedidoService;

class PedidoController extends Controller
{
    protected $pedidoService;

    public function __construct(PedidoService $pedidoService)
    {
        $this->pedidoService = $pedidoService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sucursal' => 'required|string',
            'equipo'   => 'required|string',
            'pedidos'  => 'required|array',
        ]);

        try {
            $ids = $this->pedidoService->procesarPedidos(
                $validated['sucursal'], 
                $validated['equipo'], 
                $validated['pedidos']
            );

            return response()->json(['status' => 'ok', 'procesados' => $ids], 200);
            
        } catch (\Exception $e) {
            // Loguear el error es vital para debugging
            \Log::error("Error sincronizando pedidos: " . $e->getMessage());
            
            return response()->json(['status' => 'error', 'message' => 'Error interno'], 500);
        }
    }
}
