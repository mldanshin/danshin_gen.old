<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersUnconfirmedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("users_unconfirmed", function (Blueprint $table) {
            $table->id();
            $table->integer('identifier_id');
            $table->foreign('identifier_id')
                ->references('id')
                ->on('users_identifiers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string("identifier");
            $table->string("password");
            $table->string("timestamp");
            $table->integer("attempts");
            $table->string("code");
            $table->string("repeat_timestamp");
            $table->integer("repeat_attempts");
            $table->unique(['identifier']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("users_unconfirmed");
    }
}
