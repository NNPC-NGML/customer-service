<?php

namespace App\Services;

use App\Models\CustomerEoi;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerEoiService
{
    /**
     * Returns the file extension based on the provided MIME type.
     *
     * @param string $mimeType The MIME type of the file.
     * @return string The corresponding file extension.
     */
    public function getFileExtension(string $mimeType): string
    {
        return match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'application/pdf' => 'pdf',
            'text/plain' => 'txt',
            default => 'bin',
        };
    }

    /**
     * Uploads a base64-encoded file and stores it in the filesystem.
     *
     * @param string $base64File The base64-encoded file string.
     * @return array Metadata about the uploaded file.
     */
    public function uploadBase64(string $base64File): array
    {
        $base64Data = preg_match('/^data:(\w+\/[\w\-\+]+);base64,/', $base64File)
            ? substr($base64File, strpos($base64File, ',') + 1)
            : $base64File;

        $fileData = base64_decode($base64Data, true);

        if ($fileData === false) {
            throw new \InvalidArgumentException('Invalid base64 data');
        }

        $tmpFileName = Str::random(10) . '.tmp';
        $tmpFilePath = sys_get_temp_dir() . '/' . $tmpFileName;
        file_put_contents($tmpFilePath, $fileData);

        $fileMimeType = mime_content_type($tmpFilePath);
        $fileSize = filesize($tmpFilePath);
        $extension = $this->getFileExtension($fileMimeType);

        if ($extension === 'bin') {
            unlink($tmpFilePath);
            throw new \InvalidArgumentException('Unsupported file type');
        }

        $fileName = Str::random(10) . '.' . $extension;
        $path = "eoi/{$fileName}";
        Storage::disk(config('filesystems.default'))->put($path, file_get_contents($tmpFilePath));

        unlink($tmpFilePath);

        return [
            'name' => $fileName,
            'extension' => $extension,
            'size' => $fileSize,
            'mime_type' => $fileMimeType,
            'path' => $path,
        ];
    }

    /**
     * Validate EOI data.
     *
     * @param array $data The data to validate.
     * @param bool $isUpdate Whether this is for an update operation.
     * @return array The validated data.
     * @throws ValidationException
     */
    public function validate(array $data, bool $isUpdate = false): array
    {
        $rules = $isUpdate === false ? [
            'user_id' => 'required|integer',
            'file_path' => 'required|string|max:255',
            'customer_id' => 'required|integer',
            'customer_site_id' => 'required|integer',
            'status' => 'nullable|in:pending,approved,rejected',
        ] : [
            'file_path' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|nullable|in:pending,approved,rejected',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Create a new EOI record.
     *
     * @param array $data The data to create the EOI.
     * @return CustomerEoi The created EOI record.
     * @throws \Throwable
     */
    public function create(array $data): CustomerEoi
    {
        Log::info('Creating a new EOI record', ['data' => $data]);

        try {
            if (isset($data['file_base64'])) {
                $fileMeta = $this->uploadBase64($data['file_base64']);
                $data['file_path'] = $fileMeta['path'];
            }

            $validatedData = $this->validate($data);
            return CustomerEoi::create($validatedData);
        } catch (\Throwable $e) {
            Log::error('Error creating EOI', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    /**
     * Update an existing EOI record.
     *
     * @param int $id The ID of the EOI to update.
     * @param array $data The data to update the EOI.
     * @return CustomerEoi The updated EOI record.
     * @throws \Throwable
     */
    public function update(int $id, array $data): CustomerEoi
    {
        Log::info('Updating EOI record', ['id' => $id, 'data' => $data]);

        try {
            $customerEoi = CustomerEoi::findOrFail($id);

            if (isset($data['file_base64'])) {
                $fileMeta = $this->uploadBase64($data['file_base64']);
                $data['file_path'] = $fileMeta['path'];

                if ($customerEoi->file_path) {
                    Storage::disk(config('filesystems.default'))->delete($customerEoi->file_path);
                }
            }

            $validatedData = $this->validate($data, true);
            $customerEoi->update($validatedData);

            return $customerEoi;
        } catch (\Throwable $e) {
            Log::error('Error updating EOI', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    /**
     * Get an EOI by its ID.
     *
     * @param int $id The ID of the EOI.
     * @return CustomerEoi
     */
    public function getById(int $id): CustomerEoi
    {
        return CustomerEoi::findOrFail($id);
    }

    /**
     * Get all EOIs.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return CustomerEoi::all();
    }
}
