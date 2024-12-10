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
            // $table->id();
            // $table->string("location")->comment();
            // $table->string("company_name")->comment();
            // $table->string("company_email")->comment();
            // $table->string("company_phone_number")->comment();
            // $table->string("reason")->comment();
            // $table->integer("customer_id")->nullable()->comment();
            // $table->integer("customer_site_id")->nullable()->comment();
            // $table->integer("created_by_user_id")->nullable()->comment();
            // $table->integer("approved_by_user_id")->nullable()->comment();
            // $table->boolean("status")->default(0)->comment();
            // $table->timestamps();
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('file_path');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('customer_site_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
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
