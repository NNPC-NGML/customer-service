<?php

namespace Tests\Unit;

use App\Models\CustomerApprovalType;
use App\Models\CustomerCapexApproval;
use App\Models\User;
use App\Models\CustomerSite;
use App\Services\CustomerCapexApprovalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CustomerCapexApprovalServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $approvalService;
    protected $user;
    protected $customerSite;

    protected $approvalType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->approvalService = new CustomerCapexApprovalService();

        $this->user = User::factory()->create();
        $this->customerSite = CustomerSite::factory()->create([
            'customer_id' => $this->user->id,
        ]);

        $this->approvalType = CustomerApprovalType::factory()->create();
    }

    /** @test */
    public function it_creates_a_new_customer_capex_approval()
    {
        $data = [
            'customer_id' => $this->user->id,
            'customer_site_id' => $this->customerSite->id,
            'approval_type_id' => $this->approvalType->id,
            'approved_by_user_id' => $this->user->id,
            'comment' => 'Approval comment',
            'status' => true,
        ];

        $approval = $this->approvalService->createApproval($data);

        $this->assertInstanceOf(CustomerCapexApproval::class, $approval);
        $this->assertDatabaseHas('customer_capex_approvals', $data);
    }


    /** @test */
    public function it_fails_to_create_approval_with_invalid_data()
    {
        $invalidData = [
            'customer_id' => $this->user->id,
            // Missing other required fields
        ];

        $this->expectException(ValidationException::class);

        $this->approvalService->createApproval($invalidData);

        $this->assertDatabaseMissing('customer_capex_approvals', $invalidData);
    }
}
