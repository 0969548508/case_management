<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_clients', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('client_id', 36);
            $table->longText('name');
            $table->longText('description')->nullable();
            $table->longText('default_price')->nullable();
            $table->longText('default_tax_rate')->nullable();
            $table->longText('custom_price')->nullable();
            $table->longText('custom_tax_rate')->nullable();
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
        Schema::dropIfExists('price_clients');
    }
}
