<?php

namespace App\Notifications;

use App\Models\PetaniProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class HarvestReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public PetaniProduct $product,
        public int $daysLeft,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'product_id' => $this->product->id,
            'fruit_type' => $this->product->fruit_type,
            'grade' => $this->product->grade,
            'harvest_date' => optional($this->product->harvest_date)->toDateString(),
            'days_left' => $this->daysLeft,
            'message' => $this->message(),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }

    private function message(): string
    {
        if ($this->daysLeft === 0) {
            return 'Hari ini jadwal panen sudah tiba.';
        }

        if ($this->daysLeft === 1) {
            return 'Panen tinggal 1 hari lagi.';
        }

        return "Panen tinggal {$this->daysLeft} hari lagi.";
    }
}