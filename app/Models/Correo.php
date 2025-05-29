<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correo extends Model
{
    use HasFactory;

    protected $table = 'correos';

    protected $fillable = [
        'remitente_id',
        'destinatario_id',
        'email_destinatario',
        'asunto',
        'contenido',
        'fecha_entrega',
        'ubicacion_entrega',
        'productos_solicitados',
        'leido',
        'tipo'
    ];

    protected $casts = [
        'fecha_entrega' => 'date',
        'productos_solicitados' => 'array',
        'leido' => 'boolean'
    ];

    public function remitente()
    {
        return $this->belongsTo(User::class, 'remitente_id');
    }

    public function destinatario()
    {
        return $this->belongsTo(User::class, 'destinatario_id');
    }

    public function marcarComoLeido()
    {
        $this->update(['leido' => true]);
    }
}