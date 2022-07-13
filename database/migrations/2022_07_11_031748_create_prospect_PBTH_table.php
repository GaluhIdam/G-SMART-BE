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
        Schema::create('prospect_pbth', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('prospect_id')->nullable();
            $table->text('pbth_id')->nullable();
            $table->text('product_id')->nullable();
            $table->text('ac_type_id')->nullable();
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
        Schema::dropIfExists('prospect_pbth');
    }
};
