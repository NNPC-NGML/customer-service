<?php

namespace App\Services;

use App\Models\Customer;
use App\Jobs\Customer\CreateCustomerJob;
use Illuminate\Database\Eloquent\Collection;

class CustomerService
{
    /**
     * Dispatch the CreateCustomerJob.
     *
     * @param array $data
     * @return void
     */
    public function createCustomer(array $data): void
    {
        CreateCustomerJob::dispatch($data);
    }

    /**
     * Create a new customer immediately.
     *
     * @param array $data
     * @return Customer
     */
  //  public function createCustomerSync(array $data): Customer
  //  {
        // Hash the password before saving
  //      $data['password'] = bcrypt($data['password']);
  //      return Customer::create($data);
   // }

    /**
     * Get all customers.
     *
     * @return Collection
     */
    public function getAllCustomers(): Collection
    {
        return Customer::all();
    }

    public function showCustomer(int $id): ?Customer
    {
        return Customer::find($id);
    }
}
