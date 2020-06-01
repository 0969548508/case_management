<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCasesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cases_users', function (Blueprint $table) {
            $table->char('case_id', 36);
            $table->char('user_id', 36);
            $table->foreign('case_id')->references('id')->on('cases');
            $table->foreign('user_id')->references('id')->on('users');
            $table->primary(array('case_id', 'user_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cases_users');
    }
}
