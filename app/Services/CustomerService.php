<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Jobs\Customer\CustomerCreated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;
use App\Jobs\Customer\FormBuilderNotification;
use Illuminate\Validation\ValidationException;

class CustomerService
{



    public function findAll(): Collection
    {

        return Customer::all();
    }

    public function create($data): Customer
    {


        $arrayData = json_decode($data['form_field_answers'], true);

        $structuredData = [];

        foreach ($arrayData as $item) {
            $structuredData[$item['key']]  = $item['value'];
        }

        $structuredData['task_id'] = $data['id'];


        $customerCreated = Customer::create($structuredData);

        $formBuilderNotifier['entity'] = 'Customer';
        $formBuilderNotifier['entity_id'] = $customerCreated->id;
        $formBuilderNotifier['task_id'] = $customerCreated->task_id;


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
            CustomerCreated::dispatch($customerCreated)->onQueue('formbuilder_queue');
        }

        FormBuilderNotification::dispatch($formBuilderNotifier)->onQueue('formbuilder_queue');

        return $customerCreated;
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
            'task_id' => 'required'
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
