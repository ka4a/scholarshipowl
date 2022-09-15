<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProfileOtherCountries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('profile', function(Blueprint $table) {
            $table->unsignedTinyInteger('study_country1')->nullable();
            $table->unsignedTinyInteger('study_country2')->nullable();
            $table->unsignedTinyInteger('study_country3')->nullable();
            $table->unsignedTinyInteger('study_country4')->nullable();
            $table->unsignedTinyInteger('study_country5')->nullable();

            $table->string("address2", 511)->nullable();
            $table->string("state_name", 127)->nullable();
        });

//        \DB::statement('ALTER TABLE `scholarship_owl`.`profile`
//            CHANGE COLUMN `study_country1` `study_country1` TINYINT(3) NULL DEFAULT NULL ,
//            CHANGE COLUMN `study_country2` `study_country2` TINYINT(3) NULL DEFAULT NULL ,
//            CHANGE COLUMN `study_country3` `study_country3` TINYINT(3) NULL DEFAULT NULL ,
//            CHANGE COLUMN `study_country4` `study_country4` TINYINT(3) NULL DEFAULT NULL ,
//            CHANGE COLUMN `study_country5` `study_country5` TINYINT(3) NULL DEFAULT NULL ;'
//        );

        \Schema::table('profile', function(Blueprint $table) {
            $table->foreign('study_country1', 'fk_study_country_country_id1')->references('country_id')->on('country');
            $table->foreign('study_country2', 'fk_study_country_country_id2')->references('country_id')->on('country');
            $table->foreign('study_country3', 'fk_study_country_country_id3')->references('country_id')->on('country');
            $table->foreign('study_country4', 'fk_study_country_country_id4')->references('country_id')->on('country');
            $table->foreign('study_country5', 'fk_study_country_country_id5')->references('country_id')->on('country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('profile', function(Blueprint $table) {
            $table->dropForeign('fk_study_country_country_id1');
            $table->dropForeign('fk_study_country_country_id2');
            $table->dropForeign('fk_study_country_country_id3');
            $table->dropForeign('fk_study_country_country_id4');
            $table->dropForeign('fk_study_country_country_id5');
            $table->dropColumn('address2');
            $table->dropColumn('state_name');
            $table->dropColumn('study_country1');
            $table->dropColumn('study_country2');
            $table->dropColumn('study_country3');
            $table->dropColumn('study_country4');
            $table->dropColumn('study_country5');
        });
    }
}
