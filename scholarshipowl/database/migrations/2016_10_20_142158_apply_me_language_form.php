<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApplyMeLanguageForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('apply_me_language_form', function(Blueprint $table) {
           $table->increments('id');
           $table->string('name');
           $table->string('value');
           $table->index('name');
       });

        DB::table('apply_me_language_form')->insert([
            [
                'name' => 'first page',
                'value' => 'My name %text_firstname% is %text_lastname% and my school is also cool place'
            ], [
                'name' => 'second page',
                'value' => 'This is second %text_with% controller'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('apply_me_language_form');
    }
}
