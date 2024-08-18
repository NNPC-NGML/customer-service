<?php

namespace Tests\Feature\contract;

use Tests\TestCase;
use App\Models\User;
use App\Models\CustomerContractTemplate;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerContractTemplateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_index_returns_templates()
    {
        $this->actingAsAuthenticatedTestUser();
        CustomerContractTemplate::factory(3)->create();

        $response = $this->getJson('/api/contract-templates');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_store_creates_new_template()
    {

        $this->actingAsAuthenticatedTestUser();
        $this->actingAs($this->user);
        $templateData = [
            'title' => 'New Template Test',
            'status' => 1,

        ];

        $response = $this->postJson('/api/contract-templates', $templateData);

        $response->assertStatus(201);
    }

    public function test_show_returns_specific_template()
    {
        $this->actingAsAuthenticatedTestUser();
        $template = CustomerContractTemplate::factory()->create();

        $response = $this->getJson("/api/contract-templates/{$template->id}");

        $response->assertStatus(200);
    }

    public function test_update_modifies_template()
    {
        $this->actingAsAuthenticatedTestUser();
        $this->actingAs($this->user);
        $template = CustomerContractTemplate::factory()->create();

        $updateData = [
            'title' => 'Updated Template',
            'status' => false,
        ];

        $response = $this->putJson("/api/contract-templates/{$template->id}", $updateData);

        $response->assertStatus(200);
    }

    public function test_destroy_deletes_template()
    {
        $this->actingAsAuthenticatedTestUser();
        $template = CustomerContractTemplate::factory()->create();

        $response = $this->deleteJson("/api/contract-templates/{$template->id}");

        $response->assertStatus(204);
    }
}
