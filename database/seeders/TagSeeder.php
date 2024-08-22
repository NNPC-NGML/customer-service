<?php

namespace Database\Seeders;

use App\Jobs\User\TagCreated;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Skillz\Nnpcreusable\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tags')->truncate();

        // Seed new data
        $data = [
            [
                'name' => 'Create New Customer',
                'tag_class' => 'App\Services\CustomerService',
                'tag_class_method' => 'create',
            ],
            [
                'name' => 'Create New Customer Site',
                'tag_class' => 'App\Services\CustomerSiteService',
                'tag_class_method' => 'create',
            ],
        ];
        foreach ($data as $key => $value) {
            $tags = Tag::create($value);
            TagCreated::dispatch($tags)->onQueue('formbuilder_queue');
        }
        $allTags = Tag::all();
    }
}
