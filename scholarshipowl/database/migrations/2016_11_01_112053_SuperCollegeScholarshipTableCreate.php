<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SuperCollegeScholarshipTableCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('super_college_scholarship', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36);
            $table->string('url');
            $table->string('title');
            $table->string('patron');
            $table->string('amount');
            $table->string('address1');
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip', 6);
            $table->string('deadline');
            $table->text('how_to_apply');
            $table->integer('level_min', false, true);
            $table->integer('level_max', false, true);
            $table->integer('awards', false, true);
            $table->integer('renew', false, true);
            $table->text('eligibility');
            $table->text('purpose');
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
        Schema::drop('super_college_scholarship');
    }
}
