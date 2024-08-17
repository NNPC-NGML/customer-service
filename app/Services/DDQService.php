<?php

namespace App\Services;

use App\Models\CustomerDdq;
use App\Models\CustomerDdqGroup;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DDQService
{
    const DDQ = "DDQ";

    // /**
    //  * Check if the provided name matches the DDQ constant.
    //  *
    //  * @param string $name The name to check.
    //  * @return bool Returns true if the name matches the DDQ constant, false otherwise.
    //  */
    // public function isDDQData(string $name): bool
    // {
    //     return $name === self::DDQ;
    // }

    // /**
    //  * Create a new customer DDQ entry based on the provided request data.
    //  *
    //  * @param array $request The request data containing the JSON form and other fields.
    //  * @return void
    //  * @throws ValidationException If the validation fails.
    //  */
    // public function create(array $request): void
    // {
    //     $data = is_array($request['json_form']) ? $request['json_form'] : json_decode($request['json_form'], true);
    //     $group_id = $data['group_id'];
    //     $subgroup_id = $data['subgroup_id'];
    //     $customer_id = $data['customer_id'];
    //     $customer_site_id = $data['customer_site_id'];
    //     $created_by_user_id = $data['created_by_user_id'];

    //     foreach ($data as $key => $value) {
    //         if ($key === 'group_id' || $key === 'subgroup_id') continue;
    //         if (is_array($value)) {
    //             $ddq_data = [
    //                 'data' => $value['data'],
    //                 'customer_id' => $customer_id,
    //                 'customer_site_id' => $customer_site_id,
    //                 'group_id' => $group_id,
    //                 'subgroup_id' => $subgroup_id,
    //                 'document_type' => $value['document_type'],
    //                 'created_by_user_id' => $created_by_user_id
    //             ];
    //             $data = $this->validate($ddq_data);
    //             CustomerDdq::create($data);
    //         }
    //     }
    // }

    // /**
    //  * Updates a customer's DDQ entry based on the provided request data.
    //  *
    //  * @param array $request The request data containing the JSON form and other fields.
    //  * @return void
    //  * @throws ValidationException If the validation fails.
    //  */
    // public function update(array $request): void
    // {
    //     $data = is_array($request['json_form']) ? $request['json_form'] : json_decode($request['json_form'], true);
    //     $group_id = $data['group_id'];
    //     $subgroup_id = $data['subgroup_id'];
    //     $customer_id = $data['customer_id'];
    //     $customer_site_id = $data['customer_site_id'];
    //     $created_by_user_id = $data['created_by_user_id'];

    //     foreach ($data as $key => $value) {
    //         if ($key === 'group_id' || $key === 'subgroup_id') continue;
    //         if (is_array($value)) {
    //             $ddq_data = [
    //                 'data' => $value['data'],
    //                 'customer_id' => $customer_id,
    //                 'customer_site_id' => $customer_site_id,
    //                 'group_id' => $group_id,
    //                 'subgroup_id' => $subgroup_id,
    //                 'document_type' => $value['document_type'],
    //                 'created_by_user_id' => $created_by_user_id
    //             ];
    //             $data = $this->validate($ddq_data);
    //             CustomerDdq::where('customer_id', $customer_id)
    //                 ->where('created_by_user_id', $created_by_user_id)
    //                 ->update($data);
    //         }
    //     }
    // }

    // /**
    //  * Validate the provided DDQ data.
    //  *
    //  * @param array $data The data to be validated.
    //  * @return array The validated data.
    //  * @throws ValidationException If the validation fails.
    //  */
    // public function validate(array $data): array
    // {
    //     $validator = Validator::make($data, [
    //         'data' => 'required|string',
    //         'customer_id' => 'required|integer',
    //         'customer_site_id' => 'required|integer',
    //         'group_id' => 'required|integer',
    //         'subgroup_id' => 'required|integer',
    //         'document_type' => 'required|in:string,file',
    //         'created_by_user_id' => 'required|integer',
    //     ]);

    //     if ($validator->fails()) {
    //         throw new ValidationException($validator);
    //     }

    //     return $data;
    // }

    /**
     * Validate the provided DDQ group data.
     *
     * @param array $data The data to be validated.
     * @return array The validated data.
     * @throws ValidationException If the validation fails.
     */
    public function validateDDQGroup(array $data): array
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }

    // /**
    //  * Validate the provided DDQ subgroup data.
    //  *
    //  * @param array $data The data to be validated.
    //  * @return array The validated data.
    //  * @throws ValidationException If the validation fails.
    //  */
    // public function validateDDQSubGroup(array $data): array
    // {
    //     $validator = Validator::make($data, [
    //         'title' => 'required|string|max:255',
    //         'customer_ddq_group_id' => 'required|integer',
    //         'status' => 'required|boolean',
    //     ]);

    //     if ($validator->fails()) {
    //         throw new ValidationException($validator);
    //     }

    //     return $data;
    // }

    public function getAllCustomerDdqGroups()
    {
        return CustomerDdqGroup::all();
    }

    public function createACustomerDdqGroup(array $data)
    {
        return CustomerDdqGroup::create($data);
    }

    public function findACustomerDdqGroupById(int $id)
    {
        return CustomerDdqGroup::findOrFail($id);
    }
}
