<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentRoleGenderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parent_role_gender', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gender_id');
            $table->foreign('gender_id')
                ->references('id')
                ->on('genders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('parent_id');
            $table->foreign('parent_id')
                ->references('id')
                ->on('parent_roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unique(['gender_id', 'parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parent_role_gender');
    }
}
