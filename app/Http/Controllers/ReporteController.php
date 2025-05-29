<?php
namespace App\Http\Controllers;
use App\Models\Producto;
use App\Models\Inventario;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;
class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('reporte.index');
    }
    
    public function inventario()
    {
        $inventario = Inventario::with('producto')->get();
        
        $pdf = PDF::loadView('reporte.inventario_pdf', compact('inventario'));
        
        return $pdf->download('inventario_' . now()->format('Y-m-d') . '.pdf');
    }
    
    public function productos()
    {
        $productos = Producto::with('proveedor')->get();
        
        $pdf = PDF::loadView('reporte.productos_pdf', compact('productos'));
        
        return $pdf->download('productos_' . now()->format('Y-m-d') . '.pdf');
    }
    
    public function caducados()
    {
        $today = Carbon::now();
        $inventarioCaducado = Inventario::with('producto')
            ->where('fecha_caducidad', '<', $today)
            ->get();
        
        $pdf = PDF::loadView('reporte.caducados_pdf', compact('inventarioCaducado', 'today'));
        
        return $pdf->download('productos_caducados_' . now()->format('Y-m-d') . '.pdf');
    }
    
    public function proximosCaducar()
    {
        $today = Carbon::now();
        $sevenDaysLater = Carbon::now()->addDays(7);
        
        $inventarioProximoCaducar = Inventario::with('producto')
            ->where('fecha_caducidad', '>=', $today)
            ->where('fecha_caducidad', '<=', $sevenDaysLater)
            ->get();
        
        $pdf = PDF::loadView('reporte.proximos_caducar_pdf', compact('inventarioProximoCaducar', 'today', 'sevenDaysLater'));
        
        return $pdf->download('productos_proximos_caducar_' . now()->format('Y-m-d') . '.pdf');
    }
}