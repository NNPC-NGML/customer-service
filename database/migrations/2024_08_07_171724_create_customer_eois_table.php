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
        Schema::create('customer_eois', function (Blueprint $table) {
            $table->id();
            $table->string("location")->comment();
            $table->string("company_name")->comment();
            $table->string("company_email")->comment();
            $table->string("company_phone_number")->comment();
            $table->string("reason")->comment();
            $table->integer("customer_id")->nullable()->comment();
            $table->integer("customer_site_id")->nullable()->comment();
            $table->integer("created_by_user_id")->nullable()->comment();
            $table->integer("approved_by_user_id")->nullable()->comment();
            $table->boolean("status")->default(0)->comment();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_eois');
    }
};
