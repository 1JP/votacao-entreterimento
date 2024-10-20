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
     * teste create customer via payment api
     */
    public function test_create_customer_payment_api(): void
    {
        $name = fake()->name();
        $email = fake()->unique()->safeEmail();

        $body = [
            "address" => [
                "street" => "Rua de Teste",
                "number" => "45",
                "locality" => "Bairro teste",
                "city" => "Matozinhos",
                "region_code" => "MG",
                "postal_code" => "35730000",
                "country" => "BRA"
            ],
            "name" => $name,
            "email" => $email,
            "tax_id" => '20162199040',
            "phones" => [
                [
                    "country" => "55",
                    "area" => "31",
                    "number" => "999999999"
                ]
            ]
        ];
        
        $createCustomer = $this->paymentApi->createCustomer($body);

        $this->assertTrue(isset($createCustomer->id));
        $this->assertEquals($createCustomer->name, $name);
        $this->assertEquals($createCustomer->email, $email);
        $this->assertEquals($createCustomer->tax_id, $body['tax_id']);
        $this->assertEquals($createCustomer->phones[0]->country, $body['phones'][0]['country']);
        $this->assertEquals($createCustomer->phones[0]->area, $body['phones'][0]['area']);
        $this->assertEquals($createCustomer->phones[0]->number, $body['phones'][0]['number']);

        $this->assertEquals($createCustomer->address->street, $body['address']['street']);
        $this->assertEquals($createCustomer->address->number, $body['address']['number']);
        $this->assertEquals($createCustomer->address->locality, $body['address']['locality']);
        $this->assertEquals($createCustomer->address->city, $body['address']['city']);
        $this->assertEquals($createCustomer->address->region_code, $body['address']['region_code']);
        $this->assertEquals($createCustomer->address->country, $body['address']['country']);
        $this->assertEquals($createCustomer->address->postal_code, $body['address']['postal_code']);

    }

    /**
     * teste update customer via payment api
     */
    public function test_update_customer_payment_api(): void
    {
        $name = fake()->name();
        $email = fake()->unique()->safeEmail();
        $customerId = 'CUST_F25B4BD7-5471-469E-9CB0-D21C904D0EFA';

        $body = [
            "address" => [
                "street" => "Rua de Teste Segundo",
                "number" => "45",
                "locality" => "Bairro teste Segundo",
                "city" => "Pedro Leopoldo",
                "region_code" => "MG",
                "postal_code" => "35720000",
                "country" => "BRA"
            ],
            "name" => $name,
            "email" => $email,
            "phones" => [
                [
                    "country" => "55",
                    "area" => "31",
                    "number" => "988888888"
                ]
            ]
        ];
        
        $updateCustomer = $this->paymentApi->updateDataCustomer($body, $customerId);

        $this->assertTrue(isset($updateCustomer->id));
        $this->assertEquals($updateCustomer->name, $name);
        $this->assertEquals($updateCustomer->email, $email);
        $this->assertEquals($updateCustomer->phones[0]->country, $body['phones'][0]['country']);
        $this->assertEquals($updateCustomer->phones[0]->area, $body['phones'][0]['area']);
        $this->assertEquals($updateCustomer->phones[0]->number, $body['phones'][0]['number']);

        $this->assertEquals($updateCustomer->address->street, $body['address']['street']);
        $this->assertEquals($updateCustomer->address->number, $body['address']['number']);
        $this->assertEquals($updateCustomer->address->locality, $body['address']['locality']);
        $this->assertEquals($updateCustomer->address->city, $body['address']['city']);
        $this->assertEquals($updateCustomer->address->region_code, $body['address']['region_code']);
        $this->assertEquals($updateCustomer->address->country, $body['address']['country']);
        $this->assertEquals($updateCustomer->address->postal_code, $body['address']['postal_code']);

    }

    /**
     * teste get customer via payment api
     */
    public function test_get_customer_payment_api(): void
    {
        $customerId = 'CUST_F25B4BD7-5471-469E-9CB0-D21C904D0EFA';

        $customer = $this->paymentApi->getCustomer($customerId);

        $this->assertTrue(isset($customer->id));

    }

    /**
     * teste not create customer via payment api
     */
    public function test_not_create_customer_payment_api(): void
    {
        $name = fake()->name();
        $email = fake()->unique()->safeEmail();

        $body = [
            "address" => [
                "street" => "",
                "number" => "",
                "locality" => "",
                "city" => "",
                "region_code" => "",
                "postal_code" => "",
                "country" => ""
            ],
            "name" => $name,
            "email" => $email,
            "tax_id" => '20162199040',
            "phones" => [
                [
                    "country" => "",
                    "area" => "",
                    "number" => ""
                ]
            ]
        ];
        
        $createCustomer = $this->paymentApi->createCustomer($body);
        
        $errorMessages = $createCustomer->error_messages;

        $this->assertIsArray($errorMessages);
        $expectedErrors = [
            [
                'error' => 'invalid_string_min_length',
                'parameter_name' => 'phones[0].number',
                'description' => 'The phone number is too short. It must contain from 8 to 9 digits.'
            ],
            [
                'error' => 'invalid_string_length',
                'parameter_name' => 'address.country',
                'description' => "The address country code length is incorrect. It must contain 3 characters."
            ],
            [
                'error' => 'invalid_string_length',
                'parameter_name' => 'phones[0].area',
                'description' => 'The phones area is incorrect. It must contain 2 digits.'
            ],
            [
                'error' => 'invalid_parameter',
                'parameter_name' => 'phones[0].country',
                'description' => 'The phone country is incorrect. It not be in blank and it must be a Brazilian country code such as 55.'
            ],
            [
                'error' => 'invalid_parameter',
                'parameter_name' => 'phones[0].number',
            ]
        ];
    
        foreach ($expectedErrors as $expectedError) {
            $found = false;
            foreach ($errorMessages as $errorMessage) {
                if ($errorMessage->error === $expectedError['error'] &&
                    $errorMessage->parameter_name === $expectedError['parameter_name']) {
                    $found = true;
                    break;
                }
                if (isset($expectedError['description']) &&
                $errorMessage->description === $expectedError['description']) {
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, "Erro esperado não foi encontrado: " . json_encode($expectedError));
        }

    }

    /**
     * teste not update customer via payment api
     */
    public function test_not_update_customer_payment_api(): void
    {
        $name = fake()->name();
        $email = fake()->unique()->safeEmail();
        $customerId = 'CUST_F25B4BD7-5471-469E-9CB0-D21C904D0EFA';
        $body = [
            "address" => [
                "street" => "",
                "number" => "",
                "locality" => "",
                "city" => "",
                "region_code" => "",
                "postal_code" => "",
                "country" => ""
            ],
            "name" => $name,
            "email" => $email,
            "tax_id" => '20162199040',
            "phones" => [
                [
                    "country" => "",
                    "area" => "",
                    "number" => ""
                ]
            ]
        ];

        $updateCustomer = $this->paymentApi->updateDataCustomer($body, $customerId);
        
        $errorMessages = $updateCustomer->error_messages;

        $this->assertIsArray($errorMessages);
        $expectedErrors = [
            [
                "error" =>"invalid_parameter",
                "parameter_name" =>"phones[0].area",
                "description" =>"The phone area is incorrect. It must not be in blank and it must not contain alpha characters.",
            ],
            [
                "error" =>"invalid_string_min_length",
                "parameter_name" =>"phones[0].number",
                "description" =>"The phone number is too short. It must contain from 8 to 9 digits.",
            ],
            [
                "error" =>"invalid_parameter",
                "parameter_name" =>"phones[0].country",
                "description" =>"The phone country is incorrect. It not be in blank and it must be a Brazilian country code such as 55.",
            ],
            [
                "error" =>"invalid_string_length",
                "parameter_name" =>"address.country",
                "description" =>"The address country code length is incorrect. It must contain 3 characters."
            ],
            [
                "error" =>"invalid_parameter",
                "parameter_name" =>"tax_id",
                "description" =>"It is not possible to change the tax id of the already registered customer. Create a new customer with a different tax id."
            ]
        ];
    
        foreach ($expectedErrors as $expectedError) {
            $found = false;
            foreach ($errorMessages as $errorMessage) {
                if ($errorMessage->error === $expectedError['error'] &&
                    $errorMessage->parameter_name === $expectedError['parameter_name']) {
                    $found = true;
                    break;
                }
                if (isset($expectedError['description']) &&
                $errorMessage->description === $expectedError['description']) {
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, "Erro esperado não foi encontrado: " . json_encode($expectedError));
        }

    }

    /**
     * teste not get customer via payment api
     */
    public function test_not_get_customer_payment_api(): void
    {
        $customerId = 'CUST_F25B4BD7-5471-469E-9CB0-D21C904D0EFB';

        $customer = $this->paymentApi->getCustomer($customerId);
        
        $this->assertFalse(isset($customer->id));
        
        $errorMessages = $customer->error_messages;

        $this->assertIsArray($errorMessages);
        $expectedErrors = [
            [
                "error" =>"customer_not_found",
                "parameter_name" =>"customer_id",
                "description" =>"The customer id not exists.",
            ],
        ];
    
        foreach ($expectedErrors as $expectedError) {
            $found = false;
            foreach ($errorMessages as $errorMessage) {
                if ($errorMessage->error === $expectedError['error'] &&
                    $errorMessage->parameter_name === $expectedError['parameter_name']) {
                    $found = true;
                    break;
                }
                if (isset($expectedError['description']) &&
                $errorMessage->description === $expectedError['description']) {
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, "Erro esperado não foi encontrado: " . json_encode($expectedError));
        }

        $customerId = 'asdafdaffsdfsdfsfsfsf';

        $customer = $this->paymentApi->getCustomer($customerId);

        $this->assertFalse(isset($customer->id));
        
        $errorMessages = $customer->error_messages;

        $this->assertIsArray($errorMessages);
        
        $expectedErrors = [
            [
                "error" =>"invalid_parameter",
                "parameter_name" =>"code",
                "description" =>"The customer id format is incorrect. Its format must be CUST_XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX.",
            ],
        ];
    
        foreach ($expectedErrors as $expectedError) {
            $found = false;
            foreach ($errorMessages as $errorMessage) {
                if ($errorMessage->error === $expectedError['error'] &&
                    $errorMessage->parameter_name === $expectedError['parameter_name']) {
                    $found = true;
                    break;
                }
                if (isset($expectedError['description']) &&
                $errorMessage->description === $expectedError['description']) {
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, "Erro esperado não foi encontrado: " . json_encode($expectedError));
        }
    }
}
