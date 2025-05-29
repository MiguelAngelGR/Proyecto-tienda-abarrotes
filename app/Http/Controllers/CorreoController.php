<?php

namespace App\Http\Controllers;

use App\Models\Correo;
use App\Models\User;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\CorreoProveedor;

class CorreoController extends Controller
{
    public function index()
    {
        return view('correo.index');
    }
    
    public function create()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $usuarios = User::where('role', 'dispatcher')->get();
            $proveedores = Proveedor::orderBy('nombre')->get();
            return view('correo.create', compact('usuarios', 'proveedores'));
        } else {
            $administradores = User::where('role', 'admin')->get();
            return view('correo.create', compact('administradores'));
        }
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'asunto' => 'required|string|max:255',
            'contenido' => 'required|string',
        ]);

        $remitente = auth()->user();

        try {
            if ($request->tipo_destinatario === 'externo') {
                $proveedor = Proveedor::findOrFail($request->proveedor_id);
                $emailDestino = $proveedor->email;

                Log::info("Intentando enviar correo a proveedor externo: {$emailDestino}");

                Mail::to($emailDestino)->send(new CorreoProveedor($remitente, $proveedor, $request->contenido));

                Log::info("Correo enviado correctamente a proveedor externo: {$emailDestino}");
            } else {
                $destinatario = User::findOrFail($request->destinatario_id);
                $emailDestino = $destinatario->email;

                Log::info("Intentando enviar correo a usuario interno: {$emailDestino}");

                Mail::raw($request->contenido, function ($message) use ($emailDestino, $remitente, $request) {
                    $message->to($emailDestino)
                            ->subject($request->asunto)
                            ->from($remitente->email, $remitente->name);
                });

                Log::info("Correo enviado correctamente a usuario interno: {$emailDestino}");
            }

            return redirect()->route('correo.index')->with('success', 'Correo enviado exitosamente.');

        } catch (\Exception $e) {
            Log::error("Error al enviar correo: " . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudo enviar el correo. Revisa los logs para más detalles.');
        }
    }
}