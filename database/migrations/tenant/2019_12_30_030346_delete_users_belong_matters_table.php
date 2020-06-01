<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteUsersBelongMattersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users_belong_matters');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('users_belong_matters', function (Blueprint $table) {
            $table->char('matter_id', 36);
            $table->char('user_id', 36);
            $table->primary(array('matter_id', 'user_id'));
        });
    }
}
