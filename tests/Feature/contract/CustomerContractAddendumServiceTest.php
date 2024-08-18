<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\CustomerContract;
use App\Models\CustomerContractAddendum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Contract\CustomerContractAddendumService;

class CustomerContractAddendumServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $service;
    protected $user;

    protected $contract;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CustomerContractAddendumService();
        $this->user = User::factory()->create();
        $this->contract = CustomerContract::factory()->create();
    }

    public function test_attach_addendum()
    {


        $addendumData = [
            'customer_id' => $this->user->id,
            'customer_site_id' => $this->user->id,
            'parent_contract_id' => $this->contract->id,
            'child_contract_id' => $this->contract->id,
            'status' => true,
            'created_by_user_id' => $this->user->id,
        ];

        $addendum = $this->service->attachAddendum($addendumData);

        $this->assertInstanceOf(CustomerContractAddendum::class, $addendum);
        $this->assertEquals($addendumData['customer_id'], $addendum->customer_id);
    }

    public function test_get_addendum_by_id_returns_correct_data()
    {


        $childContract = CustomerContract::factory()->create();

        $addendum = CustomerContractAddendum::factory()->create([
            'customer_id' => $this->user->id,
            'customer_site_id' => $this->user->id,
            'parent_contract_id' => $this->contract->id,
            'child_contract_id' => $childContract->id,
            'created_by_user_id' => $this->user->id,
            'status' => true,
        ]);

        $retrievedAddendum = $this->service->getAddendumById($addendum->id);
        $this->assertInstanceOf(CustomerContractAddendum::class, $retrievedAddendum);
        $this->assertEquals($addendum->id, $retrievedAddendum->id);
        $this->assertEquals($this->contract->id, $retrievedAddendum->parentContract->id);
        $this->assertEquals($childContract->id, $retrievedAddendum->childContract->id);
    }

    public function test_get_addendum_by_id_returns_null_for_nonexistent_addendum()
    {

        $retrievedAddendum = $this->service->getAddendumById(99999);
        $this->assertNull($retrievedAddendum);
    }
}
