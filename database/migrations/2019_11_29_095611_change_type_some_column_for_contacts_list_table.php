<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeSomeColumnForContactsListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts_list', function (Blueprint $table) {
            $table->longText('phone')->change();
            $table->longText('mobile')->change();
            $table->longText('fax')->change();
            $table->longText('note')->change();
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
            $table->string('phone')->change();
            $table->string('mobile')->change();
            $table->string('fax')->change();
            $table->text('note')->change();
        });
    }
}
