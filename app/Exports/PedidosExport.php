<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PedidosExport implements FromCollection, WithHeadings, WithMapping
{
    protected $pedidos;

    // Recibimos los pedidos filtrados desde el controlador
    public function __construct($pedidos)
    {
        $this->pedidos = $pedidos;
    }

    public function collection()
    {
        return $this->pedidos;
    }

    // Definimos qué columnas queremos en el Excel
    public function map($pedido): array
    {
        return [
            $pedido->fecha_emision,
            $pedido->pedidos_codigo,
            $pedido->cliente,
            $pedido->sucursal,
            $pedido->equipo,
            $pedido->facturador,
            $pedido->total,
        ];
    }

    public function headings(): array
    {
        return ['Fecha', 'Código', 'Cliente', 'Sucursal', 'PC/Equipo', 'Facturador', 'Total'];
    }
}
