<style>
    body { font-family: sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    .total { text-align: right; font-weight: bold; }
</style>

<h2>Reporte de Pedidos - Comisariato La Despensa</h2>
<p>Filtros aplicados: {{ request('desde') }} al {{ request('hasta') }}</p>

<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Código</th>
            <th>Cliente</th>
            <th>Sucursal</th>
            <th>Facturador</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pedidos as $pedido)
        <tr>
            <td>{{ $pedido->fecha_emision }}</td>
            <td>{{ $pedido->pedidos_codigo }}</td>
            <td>{{ $pedido->cliente }}</td>
            <td>{{ $pedido->sucursal }}</td>
            <td>{{ $pedido->facturador }}</td>
            <td>${{ number_format($pedido->total, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
