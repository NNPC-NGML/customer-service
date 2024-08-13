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
        Schema::create('customer_survey_capexes', function (Blueprint $table) {
            $table->id();
            $table->integer("customer_id")->comment();
            $table->integer("customer_site_id")->comment();
            $table->string("customer_proposed_daily_consumption")->comment();
            $table->string("project_cost_in_naira")->comment();
            $table->string("gas_rate_per_scuf_in_naira")->comment();
            $table->string("dollar_rate")->comment();
            $table->string("capex_file_path")->comment();
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
        Schema::dropIfExists('customer_survey_capexes');
    }
};
