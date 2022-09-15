<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FieldCountryOfStudy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('field')->insert([['field_id' => \App\Entity\Field::COUNTRY_OF_STUDY, 'name' => 'Country Of Study']]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('field')->where(['field_id' => \App\Entity\Field::COUNTRY_OF_STUDY])->delete();
    }
}
