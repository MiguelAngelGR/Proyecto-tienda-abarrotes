<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Proveedor;

class CorreoProveedor extends Mailable
{
    use Queueable, SerializesModels;

    public $remitente;
    public $proveedor;
    public $contenido;

    public function __construct(User $remitente, Proveedor $proveedor, $contenido)
    {
        $this->remitente = $remitente;
        $this->proveedor = $proveedor;
        $this->contenido = $contenido;
    }

    public function build()
    {
        return $this->subject('Mensaje desde el Sistema de Tienda')
                    ->view('emails.correo_proveedor');
    }
}
