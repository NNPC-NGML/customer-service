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
        Schema::create('customer_contract_addendums', function (Blueprint $table) {
            $table->id();
            $table->integer("customer_id")->comment();
            $table->integer("customer_site_id")->comment();
            $table->integer("parent_contract_id")->comment();
            $table->integer("child_contract_id")->comment();
            $table->integer("created_by_user_id")->comment();
            $table->boolean("status")->comment();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_contract_addendums');
    }
};
