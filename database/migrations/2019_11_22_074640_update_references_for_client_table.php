<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReferencesForClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients');
        });

        Schema::table('price_clients', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients');
        });

        Schema::table('contacts_list', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients');
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
            $table->dropForeign('locations_client_id_foreign');
        });

        Schema::table('price_clients', function (Blueprint $table) {
            $table->dropForeign('price_clients_client_id_foreign');
        });

        Schema::table('contacts_list', function (Blueprint $table) {
            $table->dropForeign('contacts_list_client_id_foreign');
        });
    }
}
