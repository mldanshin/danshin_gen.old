<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_unavailable');
            $table->integer('gender_id');
            $table->foreign('gender_id')
                ->references('id')
                ->on('genders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('surname');
            $table->string('name');
            $table->string('patronymic')->nullable();
            $table->string('birth_date');
            $table->string('birth_place');
            $table->string('death_date')->nullable();
            $table->string('burial_place')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('people');
    }
}
