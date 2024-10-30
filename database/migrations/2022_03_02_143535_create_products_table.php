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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId("categorie_id")->constrained('categories');
            $table->string("ref")->unique()->nullable();
            $table->string('name');
            $table->decimal('price',8,2);
            $table->decimal('priceAchat',8,2);
            $table->foreignId("depot_id")->constrained('depots');
            $table->string('image');
            $table->integer("QNT");
            $table->timestamps();

            //$table->foreign('categorie_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');

            //$table->foreign('depot_id')->references('id')->on('depots')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
