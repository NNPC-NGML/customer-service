<?php

namespace Tests\Feature\Job;

use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use App\Jobs\Customer\FormBuilderNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormBuilderNotificationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_it_form_builder_notification_job_runs(): void
    {
        Queue::fake();


        $request = [
            'entity' => 'Test',
            'entity_id' => '1234',
            'task_id' => '555',
        ];
        FormBuilderNotification::dispatch($request);

        Queue::assertPushed(FormBuilderNotification::class, function ($job) use ($request) {
            return $job->getData() == $request;
        });
    }
}
