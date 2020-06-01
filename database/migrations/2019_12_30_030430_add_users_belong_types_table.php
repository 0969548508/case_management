<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsersBelongTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_belong_types', function (Blueprint $table) {
            $table->char('type_id', 36);
            $table->char('user_id', 36);
            $table->foreign('type_id')->references('id')->on('types');
            $table->foreign('user_id')->references('id')->on('users');
            $table->primary(array('type_id', 'user_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_belong_types', function (Blueprint $table) {
            $table->dropForeign('users_belong_types_type_id_foreign');
            $table->dropForeign('users_belong_types_user_id_foreign');
        });

        Schema::dropIfExists('users_belong_types');
    }
}
