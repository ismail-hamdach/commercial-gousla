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
        Schema::create('details_orders', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('order_id');
            $table->string('QNT');
            $table->decimal('price',8,2);
            $table->decimal('priceAchat',8,2);
            $table->decimal('total',8,2);
            $table->timestamps();

            //  $table->foreign('product_id')->references('id')->on('products')
            //      ->onUpdate('cascade')->onDelete('cascade');

            // $table->foreign('order_id')->references('ref')->on('orders')
            //     ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('details_orders');
    }
};
