<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Jobs\Customer\CustomerCreated;
use App\Jobs\Customer\CreateCustomerJob;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerService
{
    // /**
    //  * Dispatch the CreateCustomerJob.
    //  *
    //  * @param array $data
    //  * @return void
    //  */
    // public function createCustomer(array $data): void
    // {
    //     CreateCustomerJob::dispatch($data);
    // }

    // /**
    //  * Create a new customer immediately.
    //  *
    //  * @param array $data
    //  * @return Customer
    //  */
    // //  public function createCustomerSync(array $data): Customer
    // //  {
    // // Hash the password before saving
    // //      $data['password'] = bcrypt($data['password']);
    // //      return Customer::create($data);
    // // }



    public function create(array $data): Customer
    {

        try {
            $validatedData = $this->validate($data);
            $validatedData['password'] = Hash::make($validatedData['password']);
            $customerCreated = Customer::create($validatedData);

            $customerQueue = config("nnpcreusable.CUSTOMER_CREATED");
            if (is_array($customerQueue) && !empty($customerQueue)) {
                foreach ($customerQueue as $queue) {
                    $queue = trim($queue);
                    if (!empty($queue)) {
                        Log::info("Dispatching Customer event to queue: " . $queue);
                        CustomerCreated::dispatch($customerCreated)->onQueue($queue);
                    }
                }
            } else {
                CustomerCreated::dispatch($customerCreated)->onQueue("automator_queue");
            }

            return $customerCreated;
        } catch (ValidationException $e) {
            throw $e;
        }
    }



    private function validate(array $data, $id = null, $partial = false): array
    {
        $rules = [
            'company_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($id)],
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'created_by_user_id' => 'required|integer|exists:users,id',
            'status' => 'sometimes|required|boolean',
        ];

        if (!$partial) {
            $rules = array_map(function ($rule) {
                return str_replace('sometimes|', '', $rule);
            }, $rules);
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
