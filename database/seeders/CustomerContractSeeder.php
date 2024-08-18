<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;
use App\Models\CustomerContract;
use App\Models\CustomerContractType;
use App\Models\CustomerContractSection;
use App\Models\CustomerContractAddendum;
use App\Models\CustomerContractTemplate;
use App\Models\CustomerContractSignature;
use App\Models\CustomerContractDetailsNew;
use App\Models\CustomerContractDetailsOld;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users
        $users = User::factory(10)->create();

        // Create contract templates
        CustomerContractTemplate::factory(5)->create([
            'created_by_user_id' => $users->random()->id,
        ]);

        // Create contract types
        $contractTypes = CustomerContractType::factory(3)->create();

        // Create contracts
        CustomerContract::factory(20)->create([
            'customer_id' => $users->random()->id,
            'customer_site_id' => $users->random()->id,
            'contract_type_id' => $contractTypes->random()->id,
            'created_by_user_id' => $users->random()->id,
        ])->each(function ($contract) use ($users) {
            // Create sections for each contract
            CustomerContractSection::factory(3)->create([
                'contract_id' => $contract->id,
                'customer_id' => $contract->customer_id,
                'customer_site_id' => $contract->customer_site_id,
                'created_by_user_id' => $users->random()->id,
            ])->each(function ($section) use ($users) {
                // Create details for each section
                CustomerContractDetailsNew::factory()->create([
                    'contract_id' => $section->contract_id,
                    'section_id' => $section->id,
                    'customer_id' => $section->customer_id,
                    'customer_site_id' => $section->customer_site_id,
                    'created_by_user_id' => $users->random()->id,
                ]);
            });

            // Create old details for each contract
            CustomerContractDetailsOld::factory()->create([
                'contract_id' => $contract->id,
                'customer_id' => $contract->customer_id,
                'customer_site_id' => $contract->customer_site_id,
                'created_by_user_id' => $users->random()->id,
            ]);

            // Create signatures for each contract
            CustomerContractSignature::factory(2)->create([
                'contract_id' => $contract->id,
                'customer_id' => $contract->customer_id,
                'customer_site_id' => $contract->customer_site_id,
                'created_by_user_id' => $users->random()->id,
            ]);
        });

        // Create addendums
        CustomerContractAddendum::factory(10)->create([
            'customer_id' => $users->random()->id,
            'customer_site_id' => $users->random()->id,
            'parent_contract_id' => function () {
                return CustomerContract::inRandomOrder()->first()->id;
            },
            'child_contract_id' => function () {
                return CustomerContract::inRandomOrder()->first()->id;
            },
            'created_by_user_id' => $users->random()->id,
        ]);
    }
}
