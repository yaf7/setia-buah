<?php

namespace App\Services;

use App\Models\PetaniProduct;
use App\Notifications\HarvestReminderNotification;
use Illuminate\Support\Facades\Notification;

class HarvestReminderService
{
    /**
     * Send harvest reminders for products that are due soon.
     */
    public function sendReminders(): int
    {
        $sentCount = 0;

        foreach ([7, 3, 1, 0] as $daysLeft) {
            $targetDate = now()->addDays($daysLeft)->toDateString();

            PetaniProduct::with('user')
                ->whereDate('harvest_date', $targetDate)
                ->get()
                ->each(function (PetaniProduct $product) use ($daysLeft, &$sentCount): void {
                    $user = $product->user;

                    if (! $user) {
                        return;
                    }

                    $alreadyNotified = $user->notifications()
                        ->where('type', HarvestReminderNotification::class)
                        ->get()
                        ->contains(function ($notification) use ($product, $daysLeft): bool {
                            return (int) data_get($notification->data, 'product_id') === $product->id
                                && (int) data_get($notification->data, 'days_left') === $daysLeft;
                        });

                    if ($alreadyNotified) {
                        return;
                    }

                    Notification::send($user, new HarvestReminderNotification($product, $daysLeft));
                    $sentCount++;
                });
        }

        return $sentCount;
    }
}