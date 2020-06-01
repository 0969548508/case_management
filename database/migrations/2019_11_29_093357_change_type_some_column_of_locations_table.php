<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeSomeColumnOfLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->longText('primary_phone')->change();
            $table->longText('secondary_phone')->change();
            $table->longText('fax')->change();
            $table->longText('description')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('primary_phone')->change();
            $table->string('secondary_phone')->change();
            $table->string('fax')->change();
            $table->string('description')->change();
        });
    }
}
