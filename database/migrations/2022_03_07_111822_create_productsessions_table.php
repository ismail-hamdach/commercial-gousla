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
        Schema::create('productsessions', function (Blueprint $table) {
            $table->id();
            $table->string('product_ref');
            $table->string('name');
            $table->decimal('peice',8,2);
            $table->decimal('priceAchat',8,2);
            $table->integer('QNT')->default(1);
            $table->decimal('total',8,2)->default(1);
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
        Schema::dropIfExists('productsessions');
    }
};
