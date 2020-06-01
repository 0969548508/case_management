<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountInstructingClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_instructing_client', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('matter_id', 36);
            $table->string('name')->nullable();
            $table->string('abn')->nullable();
            $table->string('billing_number')->nullable();
            $table->tinyInteger('is_account')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_instructing_client');
    }
}
