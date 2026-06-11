<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitorPedido extends Model
{
	// En MonitorPedido.php
  protected $table = 'monitor_pedidos'; // Asegúrate que coincida con tu tabla en MySQL
	protected $primaryKey = 'id'; // La columna 1 de tu captura
	public $incrementing = true;
	protected $keyType = 'int';


    protected $fillable = [
        'sucursal',
        'id_local_pedido',
        'total',
        'cliente',
        'identificacion',
        'detalles',
        'fecha_emision',
        'facturador',
        'id_facturador',
        'pedidos_codigo',
        'equipo',
        'sincronizado_perseo'
    ];
	
	
	protected $casts = [
		'detalles' => 'array', // Esto convierte el JSON de la DB a un array de PHP automáticamente
	];

}
