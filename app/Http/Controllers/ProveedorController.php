<?php
namespace App\Http\Controllers;
use App\Models\Proveedor;
use Illuminate\Http\Request;
class ProveedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $proveedores = Proveedor::paginate(10);
        return view('proveedor.index', compact('proveedores'));
    }
    
    public function create()
    {
        return view('proveedor.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'contacto' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:10',
            'notas' => 'nullable|string',
        ]);
        
        Proveedor::create($request->all());
        
        return redirect()->route('proveedor.index')
            ->with('success', 'Proveedor creado correctamente');
    }
    
    public function show(Proveedor $proveedor)
    {
        return view('proveedor.show', compact('proveedor'));
    }
    
    public function edit(Proveedor $proveedor)
    {
        return view('proveedor.edit', compact('proveedor'));
    }
    
    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'contacto' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:10',
            'notas' => 'nullable|string',
        ]);
        
        $proveedor->update($request->all());
        
        return redirect()->route('proveedor.index')
            ->with('success', 'Proveedor actualizado correctamente');
    }
    
    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();
        
        return redirect()->route('proveedor.index')
            ->with('success', 'Proveedor eliminado correctamente');
    }
}