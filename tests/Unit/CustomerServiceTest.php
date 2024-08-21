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

    /**
     * @test
     */
    public function it_can_create_a_new_customer()
    {
        $user = User::factory()->create();
        $customerData = [
            'company_name' => 'Acme Corp',
            'email' => 'john@example.com',
            'phone_number' => '555-1234',
            'password' => 'mypassword123',
            'created_by_user_id' => $user->id,
            'status' => true,
        ];

        $customerService = new CustomerService();
        $newCustomer = $customerService->create($customerData);

        $this->assertInstanceOf(Customer::class, $newCustomer);
        $this->assertEquals($customerData['company_name'], $newCustomer->company_name);
        $this->assertEquals($customerData['email'], $newCustomer->email);
        $this->assertEquals($customerData['phone_number'], $newCustomer->phone_number);
        $this->assertTrue(Hash::check($customerData['password'], $newCustomer->password));
        $this->assertEquals($customerData['created_by_user_id'], $newCustomer->created_by_user_id);
        $this->assertEquals($customerData['status'], $newCustomer->status);
    }

    /**
     * @test
     */
    public function it_throws_a_validation_exception_for_invalid_data()
    {
        $customerData = [
            'company_name' => '',
            'email' => 'invalid_email',
            'phone_number' => '',
            'password' => 'short',
            'created_by_user_id' => 'invalid_id',
            'status' => 'invalid_status',
        ];

        $customerService = new CustomerService();

        $this->expectException(ValidationException::class);
        $customerService->create($customerData);
    }
}
