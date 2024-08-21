<?php


namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerSite;
use App\Services\CustomerSiteService;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;


class CustomerSiteServiceTest extends TestCase
{
    use RefreshDatabase;

    private CustomerSiteService $service;
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = $this->app->make(CustomerSiteService::class);
    }

    /**
     * @test
     */
    public function it_can_create_a_new_customer_site()
    {
        $customerSiteData = [
            'customer_id' => Customer::factory()->create()->id,
            'site_address' => '123 Main St',
            'ngml_zone_id' => 1,
            'site_name' => 'Main Site',
            'phone_number' => '555-1234',
            'email' => 'site@example.com',
            'site_contact_person_name' => 'John Doe',
            'site_contact_person_email' => 'john@example.com',
            'site_contact_person_phone_number' => '555-5678',
            'site_existing_status' => true,
            'created_by_user_id' => User::factory()->create()->id,
            'status' => true,
        ];


        $newCustomerSite = $this->service->create($customerSiteData);

        $this->assertInstanceOf(CustomerSite::class, $newCustomerSite);
        $this->assertEquals($customerSiteData['customer_id'], $newCustomerSite->customer_id);
        $this->assertEquals($customerSiteData['site_address'], $newCustomerSite->site_address);
        $this->assertEquals($customerSiteData['ngml_zone_id'], $newCustomerSite->ngml_zone_id);
        $this->assertEquals($customerSiteData['site_name'], $newCustomerSite->site_name);
        $this->assertEquals($customerSiteData['phone_number'], $newCustomerSite->phone_number);
        $this->assertEquals($customerSiteData['email'], $newCustomerSite->email);
        $this->assertEquals($customerSiteData['site_contact_person_name'], $newCustomerSite->site_contact_person_name);
        $this->assertEquals($customerSiteData['site_contact_person_email'], $newCustomerSite->site_contact_person_email);
        $this->assertEquals($customerSiteData['site_contact_person_phone_number'], $newCustomerSite->site_contact_person_phone_number);
        $this->assertEquals($customerSiteData['site_existing_status'], $newCustomerSite->site_existing_status);
        $this->assertEquals($customerSiteData['created_by_user_id'], $newCustomerSite->created_by_user_id);
        $this->assertEquals($customerSiteData['status'], $newCustomerSite->status);
    }

    /**
     * @test
     */
    public function it_throws_a_validation_exception_for_invalid_data()
    {
        $customerSiteData = [
            'customer_id' => 'invalid_id',
            'site_address' => '',
            'ngml_zone_id' => 'invalid_zone_id',
            'site_name' => '',
            'phone_number' => '',
            'email' => 'invalid_email',
            'site_contact_person_name' => '',
            'site_contact_person_email' => 'invalid_email',
            'site_contact_person_phone_number' => '',
            'site_existing_status' => 'invalid_status',
            'created_by_user_id' => 'invalid_id',
            'status' => 'invalid_status',
        ];


        $this->expectException(ValidationException::class);
        $this->service->create($customerSiteData);
    }
}
