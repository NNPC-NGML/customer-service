<?php

namespace App\Services;

use App\Models\CustomerDdqExisting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerDdqExistingService
{


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
            'user_id' => 'required|integer',
            'file_path' => 'required|string|url',
            'customer_id' => 'required|integer',
            'customer_site_id' => 'required|integer',
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }

    /**
     * Get all customer DDQ existings.
     *
     * @return \Illuminate\Database\Eloquent\Collection|CustomerDdqExisting[]
     */
    public function getAll()
    {
        return CustomerDdqExisting::all();
    }

    /**
     * Find a customer DDQ existings by its ID.
     *
     * @param int $id The ID of the customer DDQ existings.
     * @return CustomerDdqExisting The found customer DDQ existings.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no existings is found.
     */
    public function getById(int $id)
    {
        return CustomerDdqExisting::findOrFail($id);
    }

    /**
     * Create a new customer DDQ Existing.
     *
     * @param array $data The data for creating the customer DDQ group.
     * @return CustomerDdqExisting The newly created customer DDQ group.
     */
    public function create(array $data)
    {
        return CustomerDdqExisting::create($data);
    }
}
