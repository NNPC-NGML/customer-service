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
        Schema::create('customer_template_details', function (Blueprint $table) {
            $table->id();
            $table->text("content")->comment();
            $table->integer("section_id")->comment();
            $table->integer("template_id")->comment();
            $table->integer("created_by_user_id")->comment();
            $table->boolean("status_id")->comment();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_template_details');
    }
};
