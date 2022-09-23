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
        Schema::create('prospect_tmb', function (Blueprint $table) {
            $table->id()->unsigned();

            $table->unsignedBigInteger('prospect_id')->nullable();
            $table->index('prospect_id');
            $table->foreign('prospect_id')->references('id')->on('prospects')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('tmb_id')->nullable();
            $table->index('tmb_id');
            $table->foreign('tmb_id')->references('id')->on('tmb')->onDelete('cascade')->onUpdate('cascade');
            
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
        Schema::dropIfExists('prospect_tmb');
    }
};
