<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->longText('type_name');
            $table->longText('address');
            $table->longText('country');
            $table->longText('state');
            $table->longText('city');
            $table->longText('postal_code');
            $table->boolean('is_primary')->default(0);
            $table->string('user_id', 36);
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
        Schema::dropIfExists('addresses');
    }
}
