<?php

namespace App\Services;

use App\Jobs\Customer\FormBuilderNotification;
use App\Models\CustomerDdqExisting;
use App\Models\FileObject;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerDdqExistingService
{

    /**
     * Returns the file extension based on the provided MIME type.
     *
     * @param string $mimeType The MIME type of the file.
     *
     * @return string The corresponding file extension.
     */
    public function getFileExtension(string $mimeType): string
    {
        return match ($mimeType) {
            // Images
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/bmp' => 'bmp',
            'image/svg+xml' => 'svg',
            'image/tiff' => 'tiff',
            'image/x-icon' => 'ico',
            'image/avif' => 'avif',

            // Documents
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'text/plain' => 'txt',
            'text/csv' => 'csv',
            'application/rtf' => 'rtf',
            'application/json' => 'json',

            // Default to binary for unknown types
            default => 'bin',
        };
    }



    /**
     * Uploads a base64-encoded file, decodes it, and stores it on the default filesystem disk.
     *
     * This method decodes the base64 file string, stores it temporarily, and then uploads
     * it to the configured storage disk. After uploading, it cleans up the temporary file and
     * returns a FileObject containing metadata about the uploaded file.
     *
     * @param string $base64File The base64-encoded file string, which may optionally include a data URI prefix.
     *
     * @return FileObject An object containing the uploaded file's name, extension, size, MIME type, and path.
     *
     * @throws \InvalidArgumentException If the base64 file cannot be decoded properly.
     */
    public function uploadBase64(string $base64File): FileObject
    {
        // Remove the base64 data URI scheme if present
        $base64Data = preg_match('/^data:(\w+\/[\w\-\+]+);base64,/', $base64File)
            ? substr($base64File, strpos($base64File, ',') + 1)
            : $base64File;

        // Decode the base64 file data
        $fileData = base64_decode($base64Data, true);

        // Generate a temporary file name and path
        $tmpFileName = Str::random(10) . '.tmp';
        $tmpFilePath = sys_get_temp_dir() . '/' . $tmpFileName;

        // Save the decoded data to the temporary file
        file_put_contents($tmpFilePath, $fileData);

        // Determine the file's MIME type and size
        $fileMimeType = mime_content_type($tmpFilePath);
        $fileSize = filesize($tmpFilePath);

        // Get the file extension based on the MIME type
        $extension = $this->getFileExtension($fileMimeType);
        if($extension === 'bin') {
            throw new \InvalidArgumentException('Unsupported file type');
        }

        // Generate a unique file name for storage
        $tmpName = Str::random(10) . '-' . time() .  '.' . $extension;
        $path = "uploads/{$tmpName}";

        // Get the default filesystem disk
        $disk = config('filesystems.default');

        // Store the file in the specified path
        Storage::disk($disk)->put($path, file_get_contents($tmpFilePath));

        // Remove the temporary file
        unlink($tmpFilePath);

        // Create a FileObject to hold metadata about the uploaded file
        $fileObject = new FileObject(
            $tmpName,
            $extension,
            $fileSize,
            $fileMimeType,
            $path
        );

        return $fileObject;
    }

    /**
     * Validate the provided DDQ data.
     *
     * @param array $data The data to be validated.
     * @return array The validated data.
     * @throws ValidationException If the validation fails.
     */
    public function validate(array $data, bool $is_update = false): array
    {
        $rules = [];
        $rules = $is_update === false ? [
            'user_id' => 'required|integer',
            'file_path' => 'required|string|max:255',
            'customer_id' => 'required|integer',
            'customer_site_id' => 'required|integer',
            'status' => 'nullable|in:pending,approved,rejected',
        ] : [
            // 'user_id' => 'sometimes|required|integer',
            'file_path' => 'sometimes|required|string|max:255',
            // 'customer_id' => 'sometimes|required|integer',
            // 'customer_site_id' => 'sometimes|required|integer',
            'status' => 'sometimes|nullable|in:pending,approved,rejected',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
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
     * Create a new Customer DDQ Existing record with the provided data.
     *
     * This method processes the provided form field answers, decodes base64 file data (if any),
     * uploads the file, and creates a new Customer DDQ Existing entry. It also dispatches
     * a notification to a queue for further processing.
     *
     * @param array $data The input data for creating the Customer DDQ Existing record, including:
     *                    - 'form_field_answers' (string, JSON): A JSON string representing the form field answers.
     *                    - 'id' (int): The task ID associated with the customer DDQ.
     *                    - 'file_base64' (optional, string): Base64-encoded file data for the DDQ, if present.
     * @return CustomerDdqExisting The newly created Customer DDQ Existing record.
     *
     * @throws \InvalidArgumentException If the JSON in 'form_field_answers' is invalid or if the base64-encoded file data is invalid.
     * @throws \Throwable If any unexpected error occurs during the creation process.
     */
    public function create(array $data): CustomerDdqExisting
    {
        Log::info('Starting customer ddq existing creation process', ['data' => $data]);

        try {
            // Decode the JSON data
            $arrayData = json_decode($data['form_field_answers'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('Invalid JSON in form_field_answers');
            }

            // Prepare structured data from the decoded array
            $structuredData = [];
            foreach ($arrayData as $item) {
                $structuredData[$item['key']] = $item['value'];
            }
            $structuredData['task_id'] = $data['id'];

            // Handle base64 file data if present
            if (isset($structuredData['file_base64']) && !empty($structuredData['file_base64'])) {
                $fileObject = $this->uploadBase64($structuredData['file_base64']);
                $structuredData['file_path'] = $fileObject->location;
            } else {
                $structuredData['file_path'] = null;
            }

            Log::info('Structured data prepared', ['structuredData' => $structuredData]);

            // Validate and create the Customer DDQ Existing entry
            $ddq_data = $this->validate($structuredData);
            $customerDdqExistingCreated = CustomerDdqExisting::create($ddq_data);

            // Dispatch notification to the queue
            $formBuilderNotifier = [
                'entity' => 'Customer DDQ Existing',
                'entity_id' => $customerDdqExistingCreated->id,
                'task_id' => $structuredData['task_id'],
            ];
            FormBuilderNotification::dispatch($formBuilderNotifier)->onQueue('formbuilder_queue');

            return $customerDdqExistingCreated;
        } catch (\Throwable $e) {
            Log::error('Unexpected error during customer ddq existing creation: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing Customer DDQ Existing record with the provided data.
     *
     * This method processes the provided form field answers, decodes base64 file data (if any),
     * uploads the file, and updates the existing Customer DDQ entry. It also dispatches
     * a notification to a queue for further processing.
     *
     * @param array $data The input data for updating the Customer DDQ Existing record, including:
     *                    - 'form_field_answers' (string, JSON): A JSON string representing the form field answers.
     *                    - 'file_base64' (optional, string): Base64-encoded file data for the DDQ, if present.
     * @return CustomerDdqExisting The updated Customer DDQ Existing record.
     *
     * @throws \InvalidArgumentException If the JSON in 'form_field_answers' is invalid or if the base64-encoded file data is invalid.
     * @throws \Throwable If any unexpected error occurs during the update process.
     */
    public function update(array $data): CustomerDdqExisting
    {
        // Get the ID from the data
        // This would need to be examined carefully as it clashes with formbuilder task id
        $id = $data['id'];

        Log::info('Starting customer ddq existing update process', ['id' => $id, 'data' => $data]);

        try {
            // Find the existing Customer DDQ record
            $customerDdqExisting = $this->getById($id);

            // Decode the JSON data
            $arrayData = json_decode($data['form_field_answers'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('Invalid JSON in form_field_answers');
            }

            // Prepare structured data from the decoded array
            $structuredData = [];
            foreach ($arrayData as $item) {
                $structuredData[$item['key']] = $item['value'];
            }
            $structuredData['task_id'] = $data['id'];

            // Handle base64 file data if present
            if (isset($structuredData['file_base64']) && !empty($structuredData['file_base64'])) {
                // Upload the new file and delete the old file if necessary
                $fileObject = $this->uploadBase64($structuredData['file_base64']);
                $structuredData['file_path'] = $fileObject->location;

                // Optionally, remove the old file
                if ($customerDdqExisting->file_path) {
                    Storage::disk(config('filesystems.default'))->delete($customerDdqExisting->file_path);
                }
            }

            Log::info('Structured data prepared for update', ['structuredData' => $structuredData]);

            // Validate the data before update
            $validatedData = $this->validate($structuredData, true);

            // Update the existing Customer DDQ record
            $customerDdqExisting->update($validatedData);

            // Dispatch notification to the queue
            $formBuilderNotifier = [
                'entity' => 'Customer DDQ Existing',
                'entity_id' => $customerDdqExisting->id,
                'task_id' => $structuredData['task_id'],
            ];
            FormBuilderNotification::dispatch($formBuilderNotifier)->onQueue('formbuilder_queue');

            return $customerDdqExisting;
        } catch (\Throwable $e) {
            Log::error('Unexpected error during customer ddq existing update: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
