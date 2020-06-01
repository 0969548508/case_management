<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnForUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->longText('role_id')->after('remember_token')->nullable();
            $table->longText('location_id')->after('remember_token')->nullable();
            $table->longText('phone_number', 300)->after('remember_token')->nullable();
            $table->longText('middle_name', 300)->after('remember_token')->nullable();
            $table->longText('family_name', 300)->after('remember_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
