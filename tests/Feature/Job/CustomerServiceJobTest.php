<?php

namespace Tests\Feature\Job;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use App\Jobs\Customer\CustomerCreated;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerServiceJobTest extends TestCase
{
    use RefreshDatabase;



    public function test_it_customer_service_job_runs(): void
    {
        Queue::fake();

        $user = User::factory()->create();
        $request = [
            'company_name' => 'Acme Corp',
            'email' => 'john@example.com',
            'phone_number' => '555-1234',
            'password' => 'mypassword123',
            'created_by_user_id' => $user->id,
            'status' => true,
        ];
        CustomerCreated::dispatch($request);

        Queue::assertPushed(CustomerCreated::class, function ($job) use ($request) {
            return $job->getData() == $request;
        });
    }
}
