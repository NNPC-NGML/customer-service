<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_ddqs', function (Blueprint $table) {
            $table->id();
            $table->text("data")->comment();
            $table->integer("customer_id")->comment();
            $table->integer("customer_site_id")->comment();
            $table->integer("group_id")->comment();
            $table->integer("subgroup_id")->comment();
            $table->enum("document_type", ["string", "file"])->comment();
            $table->integer("created_by_user_id")->comment();
            $table->enum('status', ['pending', 'approved', 'declined'])->default('pending')->comment('pending, approved, declined status of ddq');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_ddqs');
    }
};
