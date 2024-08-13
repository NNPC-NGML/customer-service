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
        Schema::create('customer_approval_types', function (Blueprint $table) {
            $table->id();
            $table->string("approval_title")->comment();
            $table->string("decline_action")->comment("this is a full namespace path to run when approval was declined");
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
        Schema::dropIfExists('customer_approval_types');
    }
};
