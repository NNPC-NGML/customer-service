<?php

namespace App\Jobs\Customer;

use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Skillz\Nnpcreusable\Service\CustomerService;

class CreateCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   // protected $data

     /**
     * creating a new job instance.
     *
     * @param array $data
     */
    public array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = new CustomerService();
        $service->createCustomer($this->data);
    }
}
