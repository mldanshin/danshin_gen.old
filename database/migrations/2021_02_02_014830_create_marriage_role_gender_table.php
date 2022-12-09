<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarriageRoleGenderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marriage_role_gender', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id');
            $table->integer('gender_id');
            $table->foreign('role_id')
                ->references('id')
                ->on('marriage_roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('gender_id')
                ->references('id')
                ->on('genders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unique(['role_id', 'gender_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marriage_role_gender');
    }
}
