<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteSpecificMattersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_belong_matters', function (Blueprint $table) {
            $table->dropForeign('users_belong_matters_matter_id_foreign');
            $table->dropForeign('users_belong_matters_user_id_foreign');
        });

        Schema::dropIfExists('specific_matters');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('specific_matters', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->longText('case_type');
            $table->longText('subtype');
            $table->timestamps();
        });

        Schema::table('users_belong_matters', function (Blueprint $table) {
            $table->foreign('matter_id')->references('id')->on('specific_matters');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
