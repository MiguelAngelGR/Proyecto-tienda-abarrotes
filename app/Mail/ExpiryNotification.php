<?php

namespace App\Mail;

use App\Models\InventoryItem;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpiryNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $inventoryItems;
    public $daysThreshold;

    public function __construct($inventoryItems, $daysThreshold)
    {
        $this->inventoryItems = $inventoryItems;
        $this->daysThreshold = $daysThreshold;
    }

    public function build()
    {
        return $this->subject('Alerta de Productos Próximos a Caducar')
                    ->view('emails.expiry-notification');
    }
}