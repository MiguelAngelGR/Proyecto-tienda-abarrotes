<?php
namespace App\Http\Controllers;
use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Http\Request;
use Carbon\Carbon;
class InventarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $inventario = Inventario::with('producto')->paginate(15);
        return view('inventario.index', compact('inventario'));
    }
    
    public function caducados()
    {
        $today = Carbon::now();
        $inventarioCaducado = Inventario::with('producto')
            ->where('fecha_caducidad', '<', $today)
            ->paginate(15);
        
        return view('inventario.caducados', compact('inventarioCaducado', 'today'));
    }
    
    public function proximosCaducar()
    {
        $today = Carbon::now();
        $sevenDaysLater = Carbon::now()->addDays(7);
        
        $inventarioProximoCaducar = Inventario::with('producto')
            ->where('fecha_caducidad', '>=', $today)
            ->where('fecha_caducidad', '<=', $sevenDaysLater)
            ->paginate(15);
        
        return view('inventario.proximos_caducar', compact('inventarioProximoCaducar', 'today', 'sevenDaysLater'));
    }
    
    public function destroy(Inventario $inventario)
    {
        $inventario->delete();
        
        return redirect()->route('inventario.index')
            ->with('success', 'Registro de inventario eliminado correctamente');
    }
}