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
        Schema::create('customer_contract_details_news', function (Blueprint $table) {
            $table->id();
            $table->integer("contract_id")->comment();
            $table->integer("section_id")->comment();
            $table->text("details")->comment();
            $table->integer("customer_id")->comment();
            $table->integer("customer_site_id")->comment();
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
        Schema::dropIfExists('customer_contract_details_news');
    }
};
