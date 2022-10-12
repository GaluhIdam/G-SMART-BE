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

            $table->foreignId('maintenance_id')->nullable()->constrained('maintenances');
            $table->foreignId('hangar_id')->nullable()->constrained('hangars');
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('ac_type_id')->nullable()->constrained('ac_type_id');
            $table->foreignId('component_id')->nullable()->constrained('component_id');
            $table->foreignId('engine_id')->nullable()->constrained('engine_id');
            $table->foreignId('apu_id')->nullable()->constrained('apu_id');
            $table->foreignId('ams_id')->nullable()->constrained('ams');
            $table->foreignId('line_id')->nullable()->constrained('lines');

            $table->string('ac_reg')->nullable();
            $table->decimal('value', 18, 9);
            $table->integer('tat');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('so_number')->nullable();
            $table->boolean('is_rkap')->nullable();

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
