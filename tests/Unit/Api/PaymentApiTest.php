<?php

namespace Tests\Unit\Api;

use App\Models\Plan;
use App\Services\PaymentApi;
use Database\Seeders\SettingSeeder;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentApiTest extends TestCase
{
    use RefreshDatabase;

    /** @var PaymentApi */
    protected $paymentApi;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->seed(SettingSeeder::class);

        $this->paymentApi = new PaymentApi;
        
    }

    /**
     * teste create plan via payment api
     */
    public function test_create_plan_payment_api(): void
    {
        $name = fake()->name();
        $name = substr($name, 0, 65);
        $number_film = 1;
        $number_book = 4;
        $number_serie = 10;
        $value = 45.00;

        $body = [
            "amount" => [
                "currency" => "BRL",
                "value" => 4500
            ],
            "interval" => [
                "unit" => "MONTH",
                "length" => 1
            ],
            "trial" => [
                "enabled" => false,
                "hold_setup_fee" => false
            ],
            "payment_method" => [
                "CREDIT_CARD"
            ],
            "name" => $name,
            "description" => "Teste de criação do plano"
        ];

        $newPlan = $this->paymentApi->createPlan($body);

        $plan = Plan::factory()->create([
            'name' => $name,
            'description' => $newPlan->description,
            'number_film' => $number_film,
            'number_book' => $number_book,
            'number_serie' => $number_serie,
            'value' => $value,
            'customer_id' => $newPlan->id,
            'active' => true,
        ]);

        $this->assertEquals($plan->name, $name);
        $this->assertEquals($plan->description, $newPlan->description);
        $this->assertEquals($plan->number_film, $number_film);
        $this->assertEquals($plan->number_book, $number_book);
        $this->assertEquals($plan->number_serie, $number_serie);
        $this->assertEquals($plan->value, $value);
        $this->assertEquals($plan->customer_id, $newPlan->id);
        $this->assertTrue($plan->active);
    }

    /**
     * teste update plan via payment api
     */
    public function test_update_plan_payment_api(): void
    {
        $name = fake()->name(65);
        $name = substr($name, 0, 65);
        $number_film = 10;
        $number_book = 7;
        $number_serie = 10;
        $value = 100.00;

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

        $body = [
            "amount" => [
                "currency" => "BRL",
                "value" => 10000
            ],
            "interval" => [
                "unit" => "MONTH",
                "length" => 1
            ],
            "trial" => [
                "enabled" => false,
                "hold_setup_fee" => false
            ],
            "payment_method" => [
                "CREDIT_CARD"
            ],
            "name" => $name,
            "description" => "Teste de alteração do plano"
        ];

        $updatePlan = $this->paymentApi->updatePlan($body, 'PLAN_2809AE71-51EA-4BB9-BB95-3B4C97474991');

        $plan->update([
            'name' => $updatePlan->name,
            'description' => $updatePlan->description,
            'number_film' => $number_film,
            'number_book' => $number_book,
            'number_serie' => $number_serie,
            'value' => $value,
            'customer_id' => $updatePlan->id,
            'active' => true,
        ]);

        $this->assertEquals($plan->name, $name);
        $this->assertEquals($plan->description, $updatePlan->description);
        $this->assertEquals($plan->number_film, $number_film);
        $this->assertEquals($plan->number_book, $number_book);
        $this->assertEquals($plan->number_serie, $number_serie);
        $this->assertEquals($plan->value, $value);
        $this->assertEquals($plan->customer_id, $updatePlan->id);
        $this->assertTrue($plan->active);
    }

    /**
     * teste update active plan via payment api
     */
    public function test_update_active_plan_payment_api(): void
    {
        $plan = Plan::factory()->create([
            'name' => 'Plano Teste',
            'description' => fake()->text(250),
            'number_film' => 1,
            'number_book' => 4,
            'number_serie' => 10,
            'value' => 45.00,
            'customer_id' => 'PLAN_2809AE71-51EA-4BB9-BB95-3B4C97474991',
            'active' => true,
        ]);

        $updateInactivatePlan = $this->paymentApi->inactivatePlan($plan->customer_id);

        $plan->update([
            'active' => !empty($updateInactivatePlan),
        ]);

        $this->assertFalse($plan->active);

        $updateActivePlan = $this->paymentApi->activePlan($plan->customer_id);

        $plan->update([
            'active' => empty($updateActivePlan),
        ]);

        $this->assertTrue($plan->active);
    }

    /**
     * teste get plan via payment api
     */
    public function test_get_plan_payment_api(): void
    {
        
        $plan = Plan::factory()->create([
            'name' => 'Talon Hagenes',
            'description' => fake()->text(250),
            'number_film' => 1,
            'number_book' => 4,
            'number_serie' => 10,
            'value' => 100.00,
            'customer_id' => 'PLAN_2809AE71-51EA-4BB9-BB95-3B4C97474991',
            'active' => true,
        ]);

        $getPlan = $this->paymentApi->getPlan($plan->customer_id);

        $valueGetPlan = Str::of($getPlan->amount->value / 100)->toString();
        $active = $getPlan->status == 'ACTIVE' ? true : false;

        $this->assertEquals($plan->name, $getPlan->name);
        $this->assertEquals($plan->value, $valueGetPlan);
        $this->assertEquals($plan->customer_id, $getPlan->id);
        $this->assertTrue($active);
    }

    /**
     * teste not create plan via payment api
     */
    public function test_not_create_plan_payment_api(): void
    {
        $name = str_repeat('a', 66);
        $longString = str_repeat('a', 251);

        $body = [
            "amount" => [
                "currency" => "BRL",
            ],
            "interval" => [
                "unit" => "MONTH",
                "length" => 1
            ],
            "trial" => [
                "enabled" => false,
                "hold_setup_fee" => false
            ],
            "payment_method" => [
                "CREDIT_CARD"
            ],
            "name" => $name,
            "description" => $longString
        ];

        $newPlan = $this->paymentApi->createPlan($body);
        
        $this->assertFalse(isset($newPlan->id));

        $errorMessages = $newPlan->error_messages;

        $this->assertIsArray($errorMessages);
        $expectedErrors = [
            [
                'error' => 'invalid_string_max_length',
                'parameter_name' => 'name',
                'description' => 'The name is too long. It must contain 65 characters.'
            ],
            [
                'error' => 'parameter_required_missing',
                'parameter_name' => 'amount.value',
                'description' => "No value was passed to the mandatory parameter 'amount'."
            ],
            [
                'error' => 'invalid_string_max_length',
                'parameter_name' => 'description',
                'description' => 'The description is too long. It must contain 250 characters.'
            ]
        ];
    
        foreach ($expectedErrors as $expectedError) {
            $found = false;
            foreach ($errorMessages as $errorMessage) {
                if ($errorMessage->error === $expectedError['error'] &&
                    $errorMessage->parameter_name === $expectedError['parameter_name'] &&
                    $errorMessage->description === $expectedError['description']) {
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, "Erro esperado não foi encontrado: " . json_encode($expectedError));
        }
    }

    /**
     * teste not update plan via payment api
     */
    public function test_not_update_plan_payment_api(): void
    {

        $plan = Plan::factory()->create([
            'name' => 'Plano Teste',
            'description' => fake()->text(250),
            'number_film' => 1,
            'number_book' => 4,
            'number_serie' => 10,
            'value' => 45.00,
            'customer_id' => 'PLAN_EB359681-CBD3-4FB1-92A6-460F4DAAB476',
            'active' => true,
        ]);

        $body = [
            "amount" => [
                "currency" => "BRL",
                "value" => 10000
            ],
            "interval" => [
                "unit" => "MONTH",
                "length" => 1
            ],
            "trial" => [
                "enabled" => false,
                "hold_setup_fee" => false
            ],
            "payment_method" => [
                "CREDIT_CARD"
            ],
            "name" => 'Plano Teste',
            "description" => "Teste de update de plano sem id existente"
        ];

        $updatePlanCustomerFalse = $this->paymentApi->updatePlan($body, $plan->customer_id);
        
        $this->assertFalse(isset($updatePlanCustomerFalse->id));
        
        $errorMessagesCustomerFalse = $updatePlanCustomerFalse->error_messages;
        
        $this->assertIsArray($errorMessagesCustomerFalse);
        $expectedErrors = [
            [
                "error" => "plan_not_found",
                "parameter_name" => "plan_id",
                "description" => "The plan id not exists.",
            ],
        ];
    
        foreach ($expectedErrors as $expectedError) {
            $found = false;
            foreach ($errorMessagesCustomerFalse as $errorMessage) {
                if ($errorMessage->error === $expectedError['error'] &&
                    $errorMessage->parameter_name === $expectedError['parameter_name'] &&
                    $errorMessage->description === $expectedError['description']) {
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, "Erro esperado não foi encontrado: " . json_encode($expectedError));
        }

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
        
        $body = [
            "amount" => [
                "currency" => "BRL",
                "value" => 10000
            ],
            "interval" => [
                "unit" => "MONTH",
                "length" => 1
            ],
            "trial" => [
                "enabled" => false,
                "hold_setup_fee" => false
            ],
            "payment_method" => [
                "CREDIT_CARD"
            ],
            "name" => 'Plano Teste',
            "description" => "Teste de update de customer_id formato não existente"
        ];

        $updatePlanNotFormatCustomerId = $this->paymentApi->updatePlan($body, $plan->customer_id);

        $this->assertFalse(isset($updatePlanNotFormatCustomerId->id));
        
        $errorMessagesNotFormatCustomerId = $updatePlanNotFormatCustomerId->error_messages;
        
        $this->assertIsArray($errorMessagesNotFormatCustomerId);

        $expectedErrors = [
            [
                "error" => "invalid_parameter",
                "parameter_name" => "id",
                "description" => "The plan id format is incorrect. Its format must be PLAN_XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX.",
            ],
        ];

        foreach ($expectedErrors as $expectedError) {
            $found = false;
            foreach ($errorMessagesNotFormatCustomerId as $errorMessage) {
                if ($errorMessage->error === $expectedError['error'] &&
                    $errorMessage->parameter_name === $expectedError['parameter_name'] &&
                    $errorMessage->description === $expectedError['description']) {
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, "Erro esperado não foi encontrado: " . json_encode($expectedError));
        }
    }

    /**
     * teste not update active plan via payment api
     */
    public function test_not_update_active_plan_payment_api(): void
    {
        $plan = Plan::factory()->create([
            'name' => 'Plano Teste',
            'description' => fake()->text(250),
            'number_film' => 1,
            'number_book' => 4,
            'number_serie' => 10,
            'value' => 45.00,
            'customer_id' => 'PLAN_EB359681-CBD3-4FB1-92A6-460F4DAAB474',
            'active' => true,
        ]);

        $updateInactivatePlan = $this->paymentApi->inactivatePlan($plan->customer_id);

        $this->assertFalse(isset($updateInactivatePlan->id));
        
        $errorMessagesInactivatePlan = $updateInactivatePlan->error_messages;
        
        $this->assertIsArray($errorMessagesInactivatePlan);
        $expectedErrors = [
            [
                "error" => "unable_change_status",
                "description" => "The plan cannot be changed to the same status.",
            ],
        ];
    
        foreach ($expectedErrors as $expectedError) {
            $found = false;
            foreach ($errorMessagesInactivatePlan as $errorMessage) {
                if ($errorMessage->error === $expectedError['error'] &&
                    $errorMessage->description === $expectedError['description']) {
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, "Erro esperado não foi encontrado: " . json_encode($expectedError));
        }

        $plan = Plan::factory()->create([
            'name' => 'Plano Teste',
            'description' => fake()->text(250),
            'number_film' => 1,
            'number_book' => 4,
            'number_serie' => 10,
            'value' => 45.00,
            'customer_id' => 'PLAN_2809AE71-51EA-4BB9-BB95-3B4C97474991',
            'active' => true,
        ]);

        $updateActivePlan = $this->paymentApi->activePlan($plan->customer_id);

        $this->assertFalse(isset($updateActivePlan->id));
        
        $errorMessagesActivePlan = $updateActivePlan->error_messages;
        
        $this->assertIsArray($errorMessagesActivePlan);
        $expectedErrors = [
            [
                "error" => "unable_change_status",
                "description" => "The plan cannot be changed to the same status.",
            ],
        ];
    
        foreach ($expectedErrors as $expectedError) {
            $found = false;
            foreach ($errorMessagesActivePlan as $errorMessage) {
                if ($errorMessage->error === $expectedError['error'] &&
                    $errorMessage->description === $expectedError['description']) {
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, "Erro esperado não foi encontrado: " . json_encode($expectedError));
        }
    }

    /**
     * teste not get plan via payment api
     */
    public function test_not_get_plan_payment_api(): void
    {
        
        $plan = Plan::factory()->create([
            'name' => 'Talon Hagenes',
            'description' => fake()->text(250),
            'number_film' => 1,
            'number_book' => 4,
            'number_serie' => 10,
            'value' => 100.00,
            'customer_id' => 'PLAN_2809AE71-51EA-4BB9-BB95-3B4C97474881',
            'active' => true,
        ]);

        $getPlan = $this->paymentApi->getPlan($plan->customer_id);

        $this->assertFalse(isset($getPlan->id));
        
        $errorMessagesGetPlan = $getPlan->error_messages;
        
        $this->assertIsArray($errorMessagesGetPlan);
        $expectedErrors = [
            [
                "error" => "plan_not_found",
                "parameter_name" => "plan_id",
                "description" => "The plan id not exists.",
            ],
        ];
    
        foreach ($expectedErrors as $expectedError) {
            $found = false;
            foreach ($errorMessagesGetPlan as $errorMessage) {
                if ($errorMessage->error === $expectedError['error'] &&
                    $errorMessage->parameter_name === $expectedError['parameter_name'] &&
                    $errorMessage->description === $expectedError['description']) {
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, "Erro esperado não foi encontrado: " . json_encode($expectedError));
        }
    }

    /**
     * teste create plan via payment api
     */
    public function test_create_customer_payment_api(): void
    {
        $name = fake()->name();
        $name = substr($name, 0, 65);
        $number_film = 1;
        $number_book = 4;
        $number_serie = 10;
        $value = 45.00;

        $body = [
            "amount" => [
                "currency" => "BRL",
                "value" => 4500
            ],
            "interval" => [
                "unit" => "MONTH",
                "length" => 1
            ],
            "trial" => [
                "enabled" => false,
                "hold_setup_fee" => false
            ],
            "payment_method" => [
                "CREDIT_CARD"
            ],
            "name" => $name,
            "description" => "Teste de criação do plano"
        ];

        $newPlan = $this->paymentApi->createPlan($body);

        $plan = Plan::factory()->create([
            'name' => $name,
            'description' => fake()->text(250),
            'number_film' => $number_film,
            'number_book' => $number_book,
            'number_serie' => $number_serie,
            'value' => $value,
            'customer_id' => $newPlan->id,
            'active' => true,
        ]);

        $this->assertEquals($plan->name, $name);
        $this->assertEquals($plan->number_film, $number_film);
        $this->assertEquals($plan->number_book, $number_book);
        $this->assertEquals($plan->number_serie, $number_serie);
        $this->assertEquals($plan->value, $value);
        $this->assertEquals($plan->customer_id, $newPlan->id);
        $this->assertTrue($plan->active);
    }
}
