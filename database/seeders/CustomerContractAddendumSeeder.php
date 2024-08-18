<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerContractAddendum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerContractAddendumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomerContractAddendum::factory()->count(20)->create();
    }
}
