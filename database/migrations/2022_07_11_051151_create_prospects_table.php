<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->year('year')->nullable();

            $table->unsignedBigInteger('transaction_type_id');
            $table->index('transaction_type_id');
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types');

            $table->unsignedBigInteger('prospect_type_id');
            $table->index('prospect_type_id');
            $table->foreign('prospect_type_id')->references('id')->on('prospect_types');

            $table->unsignedBigInteger('strategic_initiative_id');
            $table->index('strategic_initiative_id');
            $table->foreign('strategic_initiative_id')->references('id')->on('strategic_initiatives');

            $table->unsignedBigInteger('pm_id');
            $table->index('pm_id');
            $table->foreign('pm_id')->references('id')->on('users');

            $table->unsignedBigInteger('customer_id');
            $table->index('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prospects');
    }
};
