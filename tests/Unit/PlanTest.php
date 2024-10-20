<?php

namespace Tests\Unit;

use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class PlanTest extends TestCase
{

    use RefreshDatabase;

    /**
     * test create plan
     */
    public function test_create_plan(): void
    {
        $plan = Plan::factory()->create([
            'name' => 'Plano Teste',
            'description' => fake()->text(250),
            'number_film' => 1,
            'number_book' => 4,
            'number_serie' => 10,
            'value' => 45.00,
            'customer_id' => 'asdadafdfjijfsdjfkdsjskdjfsjfsjjf',
            'active' => true,
        ]);

        $this->assertEquals($plan->name, 'Plano Teste');
        $this->assertEquals($plan->number_film, 1);
        $this->assertEquals($plan->number_book, 4);
        $this->assertEquals($plan->number_serie, 10);
        $this->assertEquals($plan->value, 45.00);
        $this->assertEquals($plan->customer_id, 'asdadafdfjijfsdjfkdsjskdjfsjfsjjf');
        $this->assertTrue($plan->active);
    }

    /** 
     * test update plan 
     */
    public function test_update_plan(): void
    {
        $plan = Plan::factory()->create([
            'name' => 'Plano Teste',
            'description' => fake()->text(250),
            'number_film' => 1,
            'number_book' => 4,
            'number_serie' => 10,
            'value' => 45.00,
            'customer_id' => 'asdadafdfjijfsdjfkdsjskdjfsjfsjjf',
            'active' => true,
        ]);

        $plan->update([
            'name' => 'Plano Teste Update',
            'number_film' => 5,
            'number_book' => 4,
            'number_serie' => 20,
            'value' => 450.00,
            'customer_id' => 'asdadafdfjijfsdjfkdsjskdjfsjfsjjf',
            'active' => false,
        ]);

        $this->assertEquals($plan->name, 'Plano Teste Update');
        $this->assertEquals($plan->number_film, 5);
        $this->assertEquals($plan->number_book, 4);
        $this->assertEquals($plan->number_serie, 20);
        $this->assertEquals($plan->value, 450.00);
        $this->assertEquals($plan->customer_id, 'asdadafdfjijfsdjfkdsjskdjfsjfsjjf');
        $this->assertFalse($plan->active);
    }

    /** 
     * test delete plan 
     */
    public function test_delete_plan():void
    {
        $plan = Plan::factory()->create([
            'name' => 'Plano Teste',
            'number_film' => 1,
            'number_book' => 4,
            'number_serie' => 10,
            'value' => 45.00,
            'customer_id' => 'asdadafdfjijfsdjfkdsjskdjfsjfsjjf',
            'active' => true,
        ]);

        $this->assertDatabaseHas('plans', [
            'id' => $plan->id,
            'name' => 'Plano Teste',
        ]);

        $plan->delete();

        $this->assertDatabaseMissing('plans', [
            'id' => $plan->id,
            'name' => 'Plano Teste',
        ]);
    }

    /** 
     * test not create plan all wrong data
     */
    public function test_not_create_all_wrong_data_plan()
    {
        $this->expectException(InvalidArgumentException::class);

        Plan::factory()->create([
            'name' => fake()->randomDigit(),
            'number_film' => fake()->name(),
            'number_book' => fake()->name(),
            'number_serie' => fake()->name(),
            'value' => fake()->name(),
            'customer_id' => fake()->randomDigit(),
            'active' => fake()->randomDigit(),
        ]);
    }

    /**
     * test not create plan with max 45 
     */
    public function test_not_create_name_with_max_65_characters_plan()
    {
        $this->expectException(InvalidArgumentException::class);

        $longString = "Esta é uma string que contém mais de 65 caracteres, para testar a validação e outras funções.";

        Plan::factory()->create([
            'name' => $longString,
            'description' => fake()->text(250),
            'number_film' => fake()->randomDigit(),
            'number_book' => fake()->randomDigit(),
            'number_serie' => fake()->randomDigit(),
            'value' => fake()->randomFloat(),
            'customer_id' => fake()->text(100),
            'active' => fake()->boolean(),
        ]);
    }

    /**
     * test not create plan customer_id with max 100 
     */
    public function test_not_create_customer_id_with_max_100_characters_plan()
    {
        $this->expectException(InvalidArgumentException::class);

        $longString = str_repeat('a', 100);

        Plan::factory()->create([
            'name' => fake()->name(),
            'description' => fake()->text(250),
            'number_film' => fake()->randomDigit(),
            'number_book' => fake()->randomDigit(),
            'number_serie' => fake()->randomDigit(),
            'value' => fake()->randomFloat(),
            'customer_id' => $longString,
            'active' => fake()->boolean(),
        ]);
    }

    /**
     * test not create plan description with max 250 
     */
    public function test_not_create_description_with_max_250_characters_plan()
    {
        $this->expectException(InvalidArgumentException::class);

        $longString = str_repeat('a', 251);

        Plan::factory()->create([
            'name' => fake()->name(),
            'description' => $longString,
            'number_film' => fake()->randomDigit(),
            'number_book' => fake()->randomDigit(),
            'number_serie' => fake()->randomDigit(),
            'value' => fake()->randomFloat(),
            'customer_id' => fake()->text(100),
            'active' => fake()->boolean(),
        ]);
    }

}
