<?php

namespace App\Services;

use App\Models\MonitorPedido;
use Illuminate\Support\Facades\DB;

class PedidoService
{
    /**
     * Procesa una lista de pedidos entrantes desde los puntos de venta.
     */
    public function procesarPedidos(string $sucursal, string $equipo, array $pedidos): array
    {
        $procesados = [];

        DB::transaction(function () use ($sucursal, $equipo, $pedidos, &$procesados) {
            foreach ($pedidos as $pedido) {
                // Usamos updateOrCreate para evitar duplicados y actualizar estados
                $registro = MonitorPedido::updateOrCreate(
                    [
                        'id_facturador'   => $pedido['id_facturador'],
                        'id_local_pedido' => $pedido['id'],
                    ],
                    [
                        'sucursal'            => $sucursal,
                        'equipo'              => $equipo,
                        'pedidos_codigo'      => $pedido['pedidos_codigo'] ?? '',
                        'cliente'             => $pedido['cliente'] ?? '',
                        'identificacion'      => $pedido['identificacion'] ?? '',
                        'total'               => $pedido['total'] ?? 0,
                        'fecha_emision'       => $pedido['fecha'] ?? null,
                        'facturador'          => $pedido['facturador'] ?? '',
                        'detalles'            => json_encode($pedido['detalles'] ?? []),
                        'sincronizado_perseo' => (bool) ($pedido['enviado'] ?? false),
                    ]
                );

                if ($registro) {
                    $procesados[] = (int) $pedido['id'];
                }
            }
        });

        return $procesados;
    }
}
