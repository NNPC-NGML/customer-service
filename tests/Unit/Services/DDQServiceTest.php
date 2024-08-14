<?php

namespace Tests\Unit\Services;

use App\Models\CustomerDdq;
use App\Services\DDQService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DDQServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test isDDQData method.
     *
     * @return void
     */
    public function testIsDDQData()
    {
        $service = new DDQService();

        $this->assertTrue($service->isDDQData('DDQ'));
        $this->assertFalse($service->isDDQData('Non-DDQ'));
    }

    /**
     * Test create method with valid data.
     *
     * @return void
     */
    public function testCreateWithValidData()
    {
        $service = new DDQService();

        $request = [
            'json_form' => json_encode([
                'group_id' => 1,
                'subgroup_id' => 1,
                'customer_id' => 1,
                'customer_site_id' => 1,
                'created_by_user_id' => 1,
                'field_1' => [
                    'data' => 'Some data',
                    'document_type' => 'string',
                ],
            ]),
        ];

        $service->create($request);

        $this->assertDatabaseHas('customer_ddqs', [
            'data' => 'Some data',
            'customer_id' => 1,
            'customer_site_id' => 1,
            'group_id' => 1,
            'subgroup_id' => 1,
            'document_type' => 'string',
            'created_by_user_id' => 1,
        ]);
    }

    /**
     * Test create method with invalid data.
     *
     * @return void
     */
    public function testCreateWithInvalidData()
    {
        $this->expectException(ValidationException::class);

        $service = new DDQService();

        $request = [
            'json_form' => json_encode([
                'group_id' => 1,
                'subgroup_id' => 1,
                'customer_id' => 1,
                'customer_site_id' => 1,
                'created_by_user_id' => 1,
                'field_1' => [
                    'data' => '',  // Invalid: data is required
                    'document_type' => 'invalid_type',  // Invalid: document_type must be 'string' or 'file'
                ],
            ]),
        ];

        $service->create($request);
    }

    /**
     * Test validateDDQGroup method with valid data.
     *
     * @return void
     */
    public function testValidateDDQGroupWithValidData()
    {
        $service = new DDQService();

        $data = [
            'title' => 'Group Title',
            'status' => true,
        ];

        $validatedData = $service->validateDDQGroup($data);

        $this->assertEquals($data, $validatedData);
    }

    /**
     * Test validateDDQGroup method with invalid data.
     *
     * @return void
     */
    public function testValidateDDQGroupWithInvalidData()
    {
        $this->expectException(ValidationException::class);

        $service = new DDQService();

        $data = [
            'title' => '',  // Invalid: title is required
            'status' => 'not_boolean',  // Invalid: status must be a boolean
        ];

        $service->validateDDQGroup($data);
    }

    /**
     * Test validateDDQSubGroup method with valid data.
     *
     * @return void
     */
    public function testValidateDDQSubGroupWithValidData()
    {
        $service = new DDQService();

        $data = [
            'title' => 'Sub Group Title',
            'customer_ddq_group_id' => 1,
            'status' => true,
        ];

        $validatedData = $service->validateDDQSubGroup($data);

        $this->assertEquals($data, $validatedData);
    }

    /**
     * Test validateDDQSubGroup method with invalid data.
     *
     * @return void
     */
    public function testValidateDDQSubGroupWithInvalidData()
    {
        $this->expectException(ValidationException::class);

        $service = new DDQService();

        $data = [
            'title' => '',  // Invalid: title is required
            'customer_ddq_group_id' => null,  // Invalid: customer_ddq_group_id is required
            'status' => 'not_boolean',  // Invalid: status must be a boolean
        ];

        $service->validateDDQSubGroup($data);
    }
}
