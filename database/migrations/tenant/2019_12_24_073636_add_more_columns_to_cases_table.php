<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnsToCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('user_id', 36);
            $table->string('client_id', 36);
            $table->string('location_id', 36);
            $table->string('type_id', 36);
            $table->string('office_id', 36);
            $table->string('assigned', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('client_id');
            $table->dropColumn('location_id');
            $table->dropColumn('type_id');
            $table->dropColumn('office_id');
            $table->dropColumn('assigned');
        });
    }
}
