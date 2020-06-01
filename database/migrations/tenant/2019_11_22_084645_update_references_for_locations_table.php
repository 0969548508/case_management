<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReferencesForLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts_list', function (Blueprint $table) {
            $table->foreign('location_id')->references('id')->on('locations');
        });

        Schema::table('price_locations', function (Blueprint $table) {
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts_list', function (Blueprint $table) {
            $table->dropForeign('contacts_list_location_id_foreign');
        });

        Schema::table('price_locations', function (Blueprint $table) {
            $table->dropForeign('price_locations_location_id_foreign');
        });
    }
}
