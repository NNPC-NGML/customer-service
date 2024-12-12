<?php

namespace Tests\Unit\Services;

use App\Models\CustomerEoi;
use App\Services\CustomerEoiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CustomerEoiServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get the service instance.
     *
     * @return CustomerEoiService
     */
    public function getService(){
        return new CustomerEoiService();
    }

    /**
     * Test validate method with valid data.
     *
     * @return void
     */
    public function testValidateWithValidData()
    {
        $service = $this->getService();

        $data = [
            'user_id' => 1,
            'file_path' => 'http://example.com/image1.png',
            'customer_id' => 1,
            'customer_site_id' => 1,
            'status' => 'pending',
        ];

        $validatedData = $service->validate($data);

        $this->assertEquals($data, $validatedData);
    }

    /**
     * Test validate method with invalid data.
     *
     * @return void
     */
    public function testValidateWithInvalidData()
    {
        $this->expectException(ValidationException::class);

        $service = $this->getService();

        $data = [
            'user_id' => '',
            'file_path' => 'not url',
            'customer_id' => null,
            'customer_site_id' => null,
            'status' => true
        ];

        $service->validate($data);
    }
}
