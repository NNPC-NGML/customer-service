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
        Schema::create('customer_ddq_sub_groups', function (Blueprint $table) {
            $table->id();
            $table->string("title")->comment();
            $table->integer("customer_ddq_group_id")->comment();
            $table->boolean("status")->comment();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_ddq_sub_groups');
    }
};
