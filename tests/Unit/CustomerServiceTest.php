<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;


class CustomerServiceTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_can_create_a_new_customer()
    {
        $user = User::factory()->create();
        $data = [
            'form_field_answers' => json_encode([
                ['key' => 'company_name', 'value' => 'Acme Corp'],
                ['key' => 'email', 'value' => 'john@example.com'],
                ['key' => 'phone_number', 'value' => '555-1234'],
                ['key' => 'password', 'value' => 'mypassword123'],
                ['key' => 'created_by_user_id', 'value' => $user->id],
                ['key' => 'status', 'value' => true],
            ]),
            'id' => 123,
        ];

        $customerService = new CustomerService();

        $customer = $customerService->create($data);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('Acme Corp', $customer->company_name);
        $this->assertEquals('john@example.com', $customer->email);
        $this->assertEquals('555-1234', $customer->phone_number);
        $this->assertEquals($user->id, $customer->created_by_user_id);
        $this->assertEquals(true, $customer->status);
        $this->assertEquals(123, $customer->task_id);
    }



    public function test_it_can_get_all_customers()
    {

        Customer::factory()->count(3)->create();

        $user = User::factory()->create();
        $data = [
            'form_field_answers' => json_encode([
                ['key' => 'company_name', 'value' => 'Acme Corp'],
                ['key' => 'email', 'value' => 'john@example.com'],
                ['key' => 'phone_number', 'value' => '555-1234'],
                ['key' => 'password', 'value' => 'mypassword123'],
                ['key' => 'created_by_user_id', 'value' => $user->id],
                ['key' => 'status', 'value' => true],
            ]),
            'id' => 123,
        ];

        $service = new CustomerService();
        $customer = $service->create($data);
        $customers = $service->findAll();

        // Assert: Check that the collection contains the correct number of customers
        $this->assertCount(4, $customers);
        $this->assertInstanceOf(Customer::class, $customers->first());
    }
}
