<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountInstructingContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_instructing_contact', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('matter_id', 36)->nullable();
            $table->char('client_id', 36)->nullable();
            $table->char('location_id', 36)->nullable();
            $table->string('name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax')->nullable();
            $table->text('note', 300)->nullable();
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
        Schema::dropIfExists('account_instructing_contact');
    }
}
