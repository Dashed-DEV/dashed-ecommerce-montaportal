<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMontaportalIdToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashed__product_montaportal', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('dashed__products')
                ->cascadeOnDelete();
            $table->string('montaportal_id');
            $table->boolean('sync_stock')
                ->default(true);
            $table->string('error')
                ->nullable();

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
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
}
