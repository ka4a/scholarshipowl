<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HighschoolCollegeCitizenshipOtherCountries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('highschool', function(Blueprint $table) {
            $table->unsignedTinyInteger('country')->after('city');
        });

        \DB::table('highschool')->update(['country' => \App\Entity\Country::USA]);

        \Schema::table('highschool', function(Blueprint $table) {
            $table->foreign('country', 'fk_highschool_country_country_id')
                ->references('country_id')->on('country');
        });

        \Schema::table('college', function(Blueprint $table) {
            $table->unsignedTinyInteger('country')->after('college_id');
        });
        \DB::table('college')->update(['country' => \App\Entity\Country::USA]);
        \Schema::table('college', function(Blueprint $table) {
            $table->foreign('country', 'fk_college_country_country_id')
                ->references('country_id')->on('country');
        });

        \DB::statement('ALTER TABLE `profile` DROP FOREIGN key `fk_profile_citizenship`;');
        \DB::statement('ALTER TABLE `profile` CHANGE COLUMN `citizenship_id` `citizenship_id` SMALLINT(5) UNSIGNED NULL');
        \DB::statement('ALTER TABLE `citizenship` CHANGE COLUMN `citizenship_id` `citizenship_id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT');
        \DB::statement('ALTER TABLE `citizenship` AUTO_INCREMENT = 4');
        \DB::statement('INSERT INTO `citizenship` (country_id, name) SELECT country_id, name FROM country WHERE country_id <> 1 AND country_id <> 0');
        \Schema::table('profile', function(Blueprint $table) {
            $table->foreign('citizenship_id', 'fk_profile_citizenship')
                ->references('citizenship_id')->on('citizenship');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DELETE FROM citizenship WHERE country_id <> 1');
        \Schema::table('highschool', function(Blueprint $table) {
            $table->dropForeign('fk_highschool_country_country_id');
            $table->dropColumn('country');
        });
        \Schema::table('college', function(Blueprint $table) {
            $table->dropForeign('fk_college_country_country_id');
            $table->dropColumn('country');
        });
    }
}
