<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    
    protected $fillable = [
        'nombre',
        'codigo_barras',
        'descripcion',
        'precio_compra',
        'precio_venta',
        'stock',
        'proveedor_id',
        'categoria',
        'marca',
        'peso',
        'unidad_medida',
        'imagen'
    ];

    // Relación con proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    // Relación con inventario
    public function inventario()
    {
        return $this->hasMany(Inventario::class);
    }
}