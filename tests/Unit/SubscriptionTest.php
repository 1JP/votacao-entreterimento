<?php

namespace Tests\Unit;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    protected $user;

    /** @var Plan */
    protected $plan;

    public function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $this->user = User::factory()->create([
            'name' => 'Teste User',
            'email' => 'testeuser@test.com.br',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ])->assignRole($role->id);

        $this->plan = Plan::factory()->create([
            'name' => 'Plano Teste',
            'number_film' => 1,
            'number_book' => 4,
            'number_serie' => 10,
            'value' => 45.00,
            'customer_id' => fake()->text(100),
            'active' => true,
        ]);
    }

    /**
     * test create subscription
     */
    public function test_create_subscription(): void
    {
        $customerId = fake()->text(100);

        $subscription = Subscription::factory()->create([
            'plan_id' => $this->plan->id,
            'user_id' => $this->user->id,
            'status' => 'ACTIVE',
            'customer_id' => $customerId,
        ]);

        $this->assertEquals($subscription->plan_id, $this->plan->id);
        $this->assertEquals($subscription->user_id, $this->user->id);
        $this->assertEquals($subscription->status, 'ACTIVE');
        $this->assertEquals($subscription->customer_id, $customerId);
    }

    /** 
     * test update subscription 
     */
    public function test_update_subscription(): void
    {
        $customerId = fake()->text(100);

        $subscription = Subscription::factory()->create([
            'plan_id' => $this->plan->id,
            'user_id' => $this->user->id,
            'status' => 'ACTIVE',
            'customer_id' => $customerId,
        ]);

        $plan = Plan::factory()->create([
            'name' => 'Plano Teste',
            'number_film' => 1,
            'number_book' => 4,
            'number_serie' => 10,
            'value' => 45.00,
            'customer_id' => fake()->text(100),
            'active' => true,
        ]);

        $subscription->update([
            'plan_id' => $plan->id,
            'status' => 'EXPIRED',
        ]);

        $this->assertEquals($subscription->plan_id, $plan->id);
        $this->assertEquals($subscription->status, 'EXPIRED');
    }

    /** 
     * test delete subscription 
     */
    public function test_delete_subscription():void
    {
        $customerId = fake()->text(100);

        $subscription = Subscription::factory()->create([
            'plan_id' => $this->plan->id,
            'user_id' => $this->user->id,
            'status' => 'ACTIVE',
            'customer_id' => $customerId,
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'plan_id' => $this->plan->id,
        ]);

        $subscription->delete();

        $this->assertDatabaseMissing('subscriptions', [
            'id' => $subscription->id,
            'plan_id' => $this->plan->id,
        ]);
    }

    /** 
     * test not create subscription all wrong data
     */
    public function test_not_create_all_wrong_data_subscription()
    {
        $this->expectException(InvalidArgumentException::class);

        Subscription::factory()->create([
            'plan_id' => 30,
            'user_id' => 10,
            'status' => 'Hello',
            'customer_id' => 1254,
        ]);
    }

    /**
     * test not create subscription with max 100 
     */
    public function test_not_create_name_with_max_100_characters_subscription()
    {
        $this->expectException(InvalidArgumentException::class);

        $longString = str_repeat('a', 101);

        Subscription::factory()->create([
            'plan_id' => $this->plan->id,
            'user_id' => $this->user->id,
            'status' => 'ACTIVE',
            'customer_id' => $longString,
        ]);
    }
}
