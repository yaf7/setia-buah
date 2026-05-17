<?php

namespace Tests\Feature;

use App\Models\PetaniProduct;
use App\Models\User;
use App\Services\HarvestReminderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HarvestReminderNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_harvest_reminders_once_per_product_and_window(): void
    {
        $user = User::factory()->create([
            'role' => 'petani',
        ]);

        PetaniProduct::create([
            'user_id' => $user->id,
            'fruit_type' => 'Mango',
            'grade' => 'A',
            'estimated_weight_kg' => 120,
            'price_per_kg' => 15000,
            'harvest_date' => now()->addDays(3)->toDateString(),
            'status' => 'pending',
        ]);

        PetaniProduct::create([
            'user_id' => $user->id,
            'fruit_type' => 'Durian',
            'grade' => 'B',
            'estimated_weight_kg' => 80,
            'price_per_kg' => 25000,
            'harvest_date' => now()->toDateString(),
            'status' => 'pending',
        ]);

        $service = app(HarvestReminderService::class);

        $this->assertSame(2, $service->sendReminders());
        $this->assertDatabaseCount('notifications', 2);

        $this->assertSame(0, $service->sendReminders());
        $this->assertDatabaseCount('notifications', 2);
    }
}