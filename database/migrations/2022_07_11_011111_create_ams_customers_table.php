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
        Schema::create('ams_customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->index('customer_id');
            $table->foreign('customer_id')->references('id')->on('customer');

            $table->unsignedBigInteger('area_id');
            $table->index('area_id');
            $table->foreign('area_id')->references('id')->on('areas');

            $table->unsignedBigInteger('ams_id');
            $table->index('ams_id');
            $table->foreign('ams_id')->references('id')->on('ams');

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
        Schema::dropIfExists('ams_customers');
    }
};
