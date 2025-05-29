<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Obtener estadísticas para el dashboard
        $totalProducto = Product::count();
        
        $today = Carbon::now();
        $expiredCount = InventoryItem::whereDate('expiry_date', '<', $today)->count();
        
        $sevenDaysLater = Carbon::now()->addDays(7);
        $nearExpiryCount = InventoryItem::whereDate('expiry_date', '>=', $today)
                            ->whereDate('expiry_date', '<=', $sevenDaysLater)
                            ->count();
        
        // Productos con poco stock (menos de 10 unidades)
        $lowStockCount = Product::where('stock', '<', 10)->count();
        
        return view('dashboard', compact('totalProducto', 'expiredCount', 'nearExpiryCount', 'lowStockCount'));
    }
}