<?php
namespace App\Notifications;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;
    public Product $product;

    public function __construct(Product $product) {
        $this->product = $product;
    }

    public function via($notifiable): array {
        return ['database']; // Store in the DB to be shown in-app
    }

    public function toArray($notifiable): array {
        return [
            'type' => 'danger', // For styling the alert box
            'message' => "Low stock alert: '{$this->product->name}' is at {$this->product->current_stock} units.",
            'product_id' => $this->product->id,
        ];
    }
}