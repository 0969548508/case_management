<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReferencesForUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('password_histories', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('emails', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('phones', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->foreign('model_id')->references('id')->on('users');
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->foreign('model_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('password_histories', function (Blueprint $table) {
            $table->dropForeign('password_histories_user_id_foreign');
        });

        Schema::table('emails', function (Blueprint $table) {
            $table->dropForeign('emails_user_id_foreign');
        });

        Schema::table('phones', function (Blueprint $table) {
            $table->dropForeign('phones_user_id_foreign');
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropForeign('model_has_roles_model_id_foreign');
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->dropForeign('model_has_permissions_model_id_foreign');
        });
    }
}
