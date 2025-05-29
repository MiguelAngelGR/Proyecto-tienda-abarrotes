<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InventoryItem;
use App\Models\User;
use App\Mail\ExpiryNotification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendExpiryNotifications extends Command
{
    protected $signature = 'notifications:expiry';
    protected $description = 'Envía notificaciones por correo de productos próximos a caducar';

    public function handle()
    {
        $today = Carbon::now();
        $sevenDaysLater = Carbon::now()->addDays(7);
        
        $nearExpiryItems = InventoryItem::with('product')
                            ->whereDate('expiry_date', '>=', $today)
                            ->whereDate('expiry_date', '<=', $sevenDaysLater)
                            ->orderBy('expiry_date')
                            ->get();
        
        if ($nearExpiryItems->count() > 0) {
            // Enviar correo a todos los administradores
            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new ExpiryNotification($nearExpiryItems, 7));
            }
            
            $this->info('Notificaciones de caducidad enviadas con éxito.');
        } else {
            $this->info('No hay productos próximos a caducar en los próximos 7 días.');
        }
        
        return 0;
    }
}