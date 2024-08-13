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
        Schema::create('customer_contract_signatures', function (Blueprint $table) {
            $table->id();
            $table->integer("contract_id")->comment();
            $table->integer("customer_id")->comment();
            $table->integer("customer_site_id")->comment();
            $table->string("signature")->comment("this could be user id , customer id or the actual signature file path");
            $table->string("title")->comment("eg in presence off etc ");
            $table->integer("created_by_user_id")->comment();
            $table->enum("signature_type", ["user_id", "customer_id", "file_path"])->comment("this is used to determine the signature type, in order to know how to render the signature ");
            $table->boolean("status")->comment("");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_contract_signatures');
    }
};
