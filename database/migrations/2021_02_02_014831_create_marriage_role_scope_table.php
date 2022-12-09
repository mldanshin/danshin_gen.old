<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarriageRoleScopeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marriage_role_scope', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('role1_id');
            $table->integer('role2_id');
            $table->unique(['role1_id', 'role2_id']);
            $table->foreign('role1_id')
                ->references('id')
                ->on('marriage_roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('role2_id')
                ->references('id')
                ->on('marriage_roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marriage_role_scope');
    }
}
