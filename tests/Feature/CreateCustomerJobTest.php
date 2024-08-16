<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Services\CustomerService;
//use APP\Jobs\Customer\CreateCustomerJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CreateCustomerJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_customer_job_dispatched()
    {
        Queue::fake();

        $data = [
            'company_name' => 'Test Company',
            'email' => 'test@example.com',
            'phone_number' => '1234567890',
            'password' => 'password',
            'created_by_user_id' => 1,
        ];

        $service = new CustomerService();
        $service->dispatchCreateCustomerJob($data);

    Queue::assertPushed(CreateCustomerJob::class, function ($job) use ($data) {
        return $job->data === $data;
        });
    }
}