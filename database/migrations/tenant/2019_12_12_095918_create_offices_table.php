<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->longText('name');
            $table->longText('address');
            $table->longText('country');
            $table->longText('state');
            $table->longText('city');
            $table->longText('postal_code');
            $table->longText('phone_number', 300);
            $table->longText('fax_number')->nullable();
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
        Schema::dropIfExists('offices');
    }
}
