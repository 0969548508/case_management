<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersBelongMattersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_belong_matters', function (Blueprint $table) {
            $table->char('matter_id', 36);
            $table->char('user_id', 36);
            $table->foreign('matter_id')->references('id')->on('specific_matters');
            $table->foreign('user_id')->references('id')->on('users');
            $table->primary(array('matter_id', 'user_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_belong_matters');
    }
}
