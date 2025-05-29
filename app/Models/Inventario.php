<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Inventario extends Model
{
    use HasFactory;
    protected $table = 'inventario';
    
    protected $fillable = [
        'producto_id',
        'cantidad',
        'fecha_caducidad',
        'lote',
        'notas'
    ];
    protected $dates = ['fecha_caducidad'];
    // Relación con producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    
    // Método para verificar si el producto está caducado
    public function estaCaducado()
    {
        return Carbon::now()->greaterThan($this->fecha_caducidad);
    }
    
    // Método para verificar si el producto está próximo a caducar (menos de 7 días)
    public function proximoACaducar()
    {
        $diasRestantes = Carbon::now()->diffInDays($this->fecha_caducidad, false);
        return $diasRestantes >= 0 && $diasRestantes <= 7;
    }
    
    // Método para obtener el estado del producto según su fecha de caducidad
    public function getEstadoCaducidad()
    {
        if ($this->estaCaducado()) {
            return 'caducado'; // Rojo
        } elseif ($this->proximoACaducar()) {
            return 'proximo'; // Amarillo
        } else {
            return 'vigente'; // Verde
        }
    }
    // Método para obtener los días restantes hasta caducar
    public function diasRestantes()
    {
        return Carbon::now()->diffInDays($this->fecha_caducidad, false);
    }
}