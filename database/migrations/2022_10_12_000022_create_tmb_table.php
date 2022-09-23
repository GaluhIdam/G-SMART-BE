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
        Schema::create('tmb', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->integer('product_id')->nullable();
            $table->integer('ac_type_id')->nullable();
            $table->integer('component_id')->nullable();
            $table->integer('engine_id')->nullable();
            $table->integer('apu_id')->nullable();
            $table->decimal('market_share')->nullable();
            $table->text('remarks')->nullable();
            $table->text('maintenance_id')->nullable();
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
        Schema::dropIfExists('tmb');
    }
};
