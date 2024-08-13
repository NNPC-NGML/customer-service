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
        Schema::create('customer_capex_approvals', function (Blueprint $table) {
            $table->id();
            $table->integer("customer_id")->comment();
            $table->integer("customer_site_id")->comment();
            $table->integer("approval_type_id")->comment();
            $table->integer("approved_by_user_id")->comment();
            $table->string("comment")->comment();
            $table->boolean("status")->comment();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customercapex_approvals');
    }
};
