<?php

namespace Tests\Unit\Services;

use App\Models\CustomerDdq;
use App\Services\DDQService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DDQServiceTest extends TestCase
{
    // use RefreshDatabase;

    // /**
    //  * Test isDDQData method.
    //  *
    //  * @return void
    //  */
    // public function testIsDDQData()
    // {
    //     $service = new DDQService();

    //     $this->assertTrue($service->isDDQData('DDQ'));
    //     $this->assertFalse($service->isDDQData('Non-DDQ'));
    // }

    // /**
    //  * Test create method with valid data.
    //  *
    //  * @return void
    //  */
    // public function testCreateWithValidData()
    // {
    //     $service = new DDQService();

    //     $request = [
    //         'json_form' => json_encode([
    //             'group_id' => 1,
    //             'subgroup_id' => 1,
    //             'customer_id' => 1,
    //             'customer_site_id' => 1,
    //             'created_by_user_id' => 1,
    //             'field_1' => [
    //                 'data' => 'Some data',
    //                 'document_type' => 'string',
    //             ],
    //         ]),
    //     ];

    //     $service->create($request);

    //     $this->assertDatabaseHas('customer_ddqs', [
    //         'data' => 'Some data',
    //         'customer_id' => 1,
    //         'customer_site_id' => 1,
    //         'group_id' => 1,
    //         'subgroup_id' => 1,
    //         'document_type' => 'string',
    //         'created_by_user_id' => 1,
    //     ]);
    // }

    // /**
    //  * Test create method with invalid data.
    //  *
    //  * @return void
    //  */
    // public function testCreateWithInvalidData()
    // {
    //     $this->expectException(ValidationException::class);

    //     $service = new DDQService();

    //     $request = [
    //         'json_form' => json_encode([
    //             'group_id' => 1,
    //             'subgroup_id' => 1,
    //             'customer_id' => 1,
    //             'customer_site_id' => 1,
    //             'created_by_user_id' => 1,
    //             'field_1' => [
    //                 'data' => '',  // Invalid: data is required
    //                 'document_type' => 'invalid_type',  // Invalid: document_type must be 'string' or 'file'
    //             ],
    //         ]),
    //     ];

    //     $service->create($request);
    // }

    // /**
    //  * Test update method with valid data.
    //  *
    //  * @return void
    //  */
    // public function testUpdateWithValidData()
    // {
    //     // Arrange
    //     $service = new DDQService();

    //     // Create a DDQ entry to be updated
    //     $existingData = CustomerDdq::create([
    //         'data' => 'Old Data',
    //         'customer_id' => 1,
    //         'customer_site_id' => 1,
    //         'group_id' => 1,
    //         'subgroup_id' => 1,
    //         'document_type' => 'string',
    //         'created_by_user_id' => 1,
    //     ]);

    //     // Request data to update the existing DDQ entry
    //     $request = [
    //         'json_form' => json_encode([
    //             'group_id' => 1,
    //             'subgroup_id' => 1,
    //             'customer_id' => 1,
    //             'customer_site_id' => 1,
    //             'created_by_user_id' => 1,
    //             'field_1' => [
    //                 'data' => 'Updated Data',
    //                 'document_type' => 'file',
    //             ],
    //         ]),
    //     ];

    //     // Act
    //     $service->update($request);

    //     // Assert
    //     $this->assertDatabaseHas('customer_ddqs', [
    //         'data' => 'Updated Data',
    //         'customer_id' => 1,
    //         'customer_site_id' => 1,
    //         'group_id' => 1,
    //         'subgroup_id' => 1,
    //         'document_type' => 'file',
    //         'created_by_user_id' => 1,
    //     ]);

    //     $this->assertDatabaseMissing('customer_ddqs', [
    //         'data' => 'Old Data',
    //     ]);
    // }

    // /**
    //  * Test update method with invalid data.
    //  *
    //  * @return void
    //  */
    // public function testUpdateWithInvalidData()
    // {
    //     // Arrange
    //     $this->expectException(ValidationException::class);
    //     $service = new DDQService();

    //     // Create a DDQ entry to be updated
    //     $existingData = CustomerDdq::create([
    //         'data' => 'Old Data',
    //         'customer_id' => 1,
    //         'customer_site_id' => 1,
    //         'group_id' => 1,
    //         'subgroup_id' => 1,
    //         'document_type' => 'string',
    //         'created_by_user_id' => 1,
    //     ]);

    //     // Request data to update the existing DDQ entry with invalid data
    //     $request = [
    //         'json_form' => json_encode([
    //             'group_id' => 1,
    //             'subgroup_id' => 1,
    //             'customer_id' => 1,
    //             'customer_site_id' => 1,
    //             'created_by_user_id' => 1,
    //             'field_1' => [
    //                 'data' => '',  // Invalid: data is required
    //                 'document_type' => 'invalid_type',  // Invalid: must be 'string' or 'file'
    //             ],
    //         ]),
    //     ];

    //     // Act
    //     $service->update($request);
    // }

    // /**
    //  * Test validateDDQGroup method with valid data.
    //  *
    //  * @return void
    //  */
    // public function testValidateDDQGroupWithValidData()
    // {
    //     $service = new DDQService();

    //     $data = [
    //         'title' => 'Group Title',
    //         'status' => true,
    //     ];

    //     $validatedData = $service->validateDDQGroup($data);

    //     $this->assertEquals($data, $validatedData);
    // }

    // /**
    //  * Test validateDDQGroup method with invalid data.
    //  *
    //  * @return void
    //  */
    // public function testValidateDDQGroupWithInvalidData()
    // {
    //     $this->expectException(ValidationException::class);

    //     $service = new DDQService();

    //     $data = [
    //         'title' => '',  // Invalid: title is required
    //         'status' => 'not_boolean',  // Invalid: status must be a boolean
    //     ];

    //     $service->validateDDQGroup($data);
    // }

    // /**
    //  * Test validateDDQSubGroup method with valid data.
    //  *
    //  * @return void
    //  */
    // public function testValidateDDQSubGroupWithValidData()
    // {
    //     $service = new DDQService();

    //     $data = [
    //         'title' => 'Sub Group Title',
    //         'customer_ddq_group_id' => 1,
    //         'status' => true,
    //     ];

    //     $validatedData = $service->validateDDQSubGroup($data);

    //     $this->assertEquals($data, $validatedData);
    // }

    // /**
    //  * Test validateDDQSubGroup method with invalid data.
    //  *
    //  * @return void
    //  */
    // public function testValidateDDQSubGroupWithInvalidData()
    // {
    //     $this->expectException(ValidationException::class);

    //     $service = new DDQService();

    //     $data = [
    //         'title' => '',  // Invalid: title is required
    //         'customer_ddq_group_id' => null,  // Invalid: customer_ddq_group_id is required
    //         'status' => 'not_boolean',  // Invalid: status must be a boolean
    //     ];

    //     $service->validateDDQSubGroup($data);
    // }
}
