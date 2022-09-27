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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id');
            $table->index('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            
            $table->unsignedBigInteger('prospect_id');
            $table->index('prospect_id');
            $table->foreign('prospect_id')->references('id')->on('prospects')->onDelete('cascade')->onUpdate('cascade');
            
            $table->unsignedBigInteger('maintenance_id');
            $table->index('maintenance_id');
            $table->foreign('maintenance_id')->references('id')->on('maintenances')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('hangar_id');
            $table->index('hangar_id');
            $table->foreign('hangar_id')->references('id')->on('hangars')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('product_id');
            $table->index('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('ac_type_id');
            $table->index('ac_type_id');
            $table->foreign('ac_type_id')->references('id')->on('ac_type_id')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('component_id');
            $table->index('component_id');
            $table->foreign('component_id')->references('id')->on('component_id')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('engine_id');
            $table->index('engine_id');
            $table->foreign('engine_id')->references('id')->on('engine_id')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('apu_id');
            $table->index('apu_id');
            $table->foreign('apu_id')->references('id')->on('apu_id')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('ams_id');
            $table->index('ams_id');
            $table->foreign('ams_id')->references('id')->on('ams')->onDelete('cascade')->onUpdate('cascade');

            $table->string('ac_reg');
            $table->decimal('value');
            $table->integer('tat');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('so_number')->nullable();
            $table->boolean('is_rkap');

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
        Schema::dropIfExists('sales');
    }
};
