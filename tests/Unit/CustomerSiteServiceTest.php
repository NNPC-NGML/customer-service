<?php

namespace Tests\Unit;

// use App\Models\User;
// use App\Models\CustomerSite;
// use PHPUnit\Framework\TestCase;
// use App\Services\CustomerSiteService;



use Tests\TestCase;
use App\Models\User;
use App\Models\CustomerSite;
use App\Services\CustomerSiteService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerSiteServiceTest extends TestCase

{

    use RefreshDatabase;
    public function test_it_can_create_a_new_customer_site()
    {
        $user = User::factory()->create();
        $data = [
            'form_field_answers' => json_encode([
                ['key' => 'task_id', 'value' => 123],
                ['key' => 'customer_id', 'value' => 456],
                ['key' => 'site_address', 'value' => '123 Main St'],
                ['key' => 'ngml_zone_id', 'value' => 789],
                ['key' => 'site_name', 'value' => 'Acme Corp Site'],
                ['key' => 'phone_number', 'value' => '555-1234'],
                ['key' => 'email', 'value' => 'site@example.com'],
                ['key' => 'site_contact_person_name', 'value' => 'John Doe'],
                ['key' => 'site_contact_person_email', 'value' => 'johndoe@example.com'],
                ['key' => 'site_contact_person_phone_number', 'value' => '555-5678'],
                ['key' => 'site_contact_person_signature', 'value' => null], // assuming nullable field
                ['key' => 'site_existing_status', 'value' => false],
                ['key' => 'created_by_user_id', 'value' => $user->id],
                ['key' => 'status', 'value' => true],
            ]),
            'id' => 123,
        ];

        $service = new CustomerSiteService();
        $site = $service->create($data);

        $this->assertInstanceOf(CustomerSite::class, $site);
        $this->assertEquals(123, $site->task_id);
        $this->assertEquals(456, $site->customer_id);
        $this->assertEquals('123 Main St', $site->site_address);
        $this->assertEquals(789, $site->ngml_zone_id);
        $this->assertEquals('Acme Corp Site', $site->site_name);
        $this->assertEquals('555-1234', $site->phone_number);
        $this->assertEquals('site@example.com', $site->email);
        $this->assertEquals('John Doe', $site->site_contact_person_name);
        $this->assertEquals('johndoe@example.com', $site->site_contact_person_email);
        $this->assertEquals('555-5678', $site->site_contact_person_phone_number);
        $this->assertNull($site->site_contact_person_signature);
        $this->assertFalse($site->site_existing_status);
        $this->assertEquals($user->id, $site->created_by_user_id);
        $this->assertTrue($site->status);
    }
}
