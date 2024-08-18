<?php

namespace App\Services;

use App\Models\CustomerDdq;
use App\Models\CustomerDdqGroup;
use App\Models\CustomerDdqSubGroup;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DDQService
{
    const DDQ = "DDQ";

    /**
     * Check if the provided name matches the DDQ constant.
     *
     * @param string $name The name to check.
     * @return bool Returns true if the name matches the DDQ constant, false otherwise.
     */
    public function isDDQData(string $name): bool
    {
        return $name === self::DDQ;
    }

    /**
     * Create a new customer DDQ entry based on the provided request data.
     *
     * @param array $request The request data containing the JSON form and other fields.
     * @return void
     * @throws ValidationException If the validation fails.
     */
    public function create(array $request): void
    {
        $data = is_array($request['json_form']) ? $request['json_form'] : json_decode($request['json_form'], true);
        $group_id = $data['group_id'];
        $subgroup_id = $data['subgroup_id'];
        $customer_id = $data['customer_id'];
        $customer_site_id = $data['customer_site_id'];
        $created_by_user_id = $data['created_by_user_id'];

        foreach ($data as $key => $value) {
            if ($key === 'group_id' || $key === 'subgroup_id') continue;
            if (is_array($value)) {
                $ddq_data = [
                    'data' => $value['data'],
                    'customer_id' => $customer_id,
                    'customer_site_id' => $customer_site_id,
                    'group_id' => $group_id,
                    'subgroup_id' => $subgroup_id,
                    'document_type' => $value['document_type'],
                    'created_by_user_id' => $created_by_user_id
                ];
                $data = $this->validate($ddq_data);
                CustomerDdq::create($data);
            }
        }
    }

    /**
     * Updates a customer's DDQ entry based on the provided request data.
     *
     * @param array $request The request data containing the JSON form and other fields.
     * @return void
     * @throws ValidationException If the validation fails.
     */
    public function update(array $request): void
    {
        $data = is_array($request['json_form']) ? $request['json_form'] : json_decode($request['json_form'], true);
        $group_id = $data['group_id'];
        $subgroup_id = $data['subgroup_id'];
        $customer_id = $data['customer_id'];
        $customer_site_id = $data['customer_site_id'];
        $created_by_user_id = $data['created_by_user_id'];

        foreach ($data as $key => $value) {
            if ($key === 'group_id' || $key === 'subgroup_id') continue;
            if (is_array($value)) {
                $ddq_data = [
                    'data' => $value['data'],
                    'customer_id' => $customer_id,
                    'customer_site_id' => $customer_site_id,
                    'group_id' => $group_id,
                    'subgroup_id' => $subgroup_id,
                    'document_type' => $value['document_type'],
                    'created_by_user_id' => $created_by_user_id
                ];
                $data = $this->validate($ddq_data);
                CustomerDdq::where('customer_id', $customer_id)
                    ->where('created_by_user_id', $created_by_user_id)
                    ->update($data);
            }
        }
    }

    /**
     * Validate the provided DDQ data.
     *
     * @param array $data The data to be validated.
     * @return array The validated data.
     * @throws ValidationException If the validation fails.
     */
    public function validate(array $data): array
    {
        $validator = Validator::make($data, [
            'data' => 'required|string',
            'customer_id' => 'required|integer',
            'customer_site_id' => 'required|integer',
            'group_id' => 'required|integer',
            'subgroup_id' => 'required|integer',
            'document_type' => 'required|in:string,file',
            'created_by_user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }

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

    /**
     * Validate the provided DDQ subgroup data.
     *
     * @param array $data The data to be validated.
     * @return array The validated data.
     * @throws ValidationException If the validation fails.
     */
    public function validateDDQSubGroup(array $data): array
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'customer_ddq_group_id' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }

    /**
     * Get all customer DDQ groups.
     *
     * @return \Illuminate\Database\Eloquent\Collection|CustomerDdqGroup[]
     */
    public function getAllCustomerDdqGroups()
    {
        return CustomerDdqGroup::all();
    }

    /**
     * Create a new customer DDQ group.
     *
     * @param array $data The data for creating the customer DDQ group.
     * @return CustomerDdqGroup The newly created customer DDQ group.
     */
    public function createACustomerDdqGroup(array $data)
    {
        return CustomerDdqGroup::create($data);
    }

    /**
     * Find a customer DDQ group by its ID.
     *
     * @param int $id The ID of the customer DDQ group.
     * @return CustomerDdqGroup The found customer DDQ group.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no group is found.
     */
    public function getCustomerDdqGroupById(int $id)
    {
        return CustomerDdqGroup::findOrFail($id);
    }

    /**
     * Get all customer DDQ subgroups.
     *
     * @return \Illuminate\Database\Eloquent\Collection|CustomerDdqSubGroup[]
     */
    public function getAllCustomerDdqSubGroups()
    {
        return CustomerDdqSubGroup::all();
    }

    /**
     * Create a new customer DDQ subgroup.
     *
     * @param array $data The data for creating the customer DDQ subgroup.
     * @return CustomerDdqSubGroup The newly created customer DDQ subgroup.
     */
    public function createACustomerDdqSubGroup(array $data)
    {
        return CustomerDdqSubGroup::create($data);
    }

    /**
     * Find a customer DDQ subgroup by its ID.
     *
     * @param int $id The ID of the customer DDQ subgroup.
     * @return CustomerDdqSubGroup The found customer DDQ subgroup.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no subgroup is found.
     */
    public function getCustomerDdqSubGroupById(int $id)
    {
        return CustomerDdqSubGroup::findOrFail($id);
    }


    /**
     * Find a customer DDQ by its ID.
     *
     * @param int $id The ID of the customer DDQ.
     * @return CustomerDdqSubGroup The found customer DDQ.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no DDQ is found.
     */
    public function getCustomerDdqById(int $id)
    {
        return CustomerDdq::findOrFail($id);
    }

    /**
     * Retrieve Customer DDQs based on specified parameters.
     *
     * @param int $customer_id The ID of the customer.
     * @param int $site_id The ID of the customer site.
     * @param int $group_id The ID of the group.
     * @param int $subgroup_id The ID of the subgroup.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of `CustomerDdq` models that match the criteria.
     */
    public function getCustomerDdqByParams($customer_id, $site_id, $group_id, $subgroup_id)
    {
        return CustomerDdq::where('customer_id', $customer_id)
            ->where('customer_site_id', $site_id)
            ->where('group_id', $group_id)
            ->where('subgroup_id', $subgroup_id)
            ->get();
    }
}
