<?php

namespace Tests\Feature;

//use App\Models\Customer;
//use App\Services\CustomerService;
use App\Jobs\Customer\CreateCustomerJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CreateCustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the customer creation job.
     *
     * @return void
     */
    public function test_customer_creation_job()
    {
        Queue::fake();

        $data = [
            'company_name' => 'Test Company',
            'email' => 'test@example.com',
            'phone_number' => '1234567890',
            'password' => bcrypt('password'),
            'created_by_user_id' => 1,
            'status' => true,
        ];

        // Dispatch the job
        CreateCustomerJob::dispatch($data);

        // Assert that the job was pushed onto the queue
        Queue::assertPushed(CreateCustomerJob::class, function ($job) use ($data) {
            return $job->data['email'] === $data['email'];
        });
    }
}