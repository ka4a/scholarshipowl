<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateJollieSchoolSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE `zu_usa_campaign` SET `submission_url`='http://sys.choosemydegree.com/ex-post/jolie/' WHERE `zu_usa_campaign_id`='4';");

        DB::statement("UPDATE `zu_usa_campus` SET `submission_value`='16' WHERE `zu_usa_campus_id`='78';");
        DB::statement("UPDATE `zu_usa_campus` SET `submission_value`='29' WHERE `zu_usa_campus_id`='74';");
        DB::statement("UPDATE `zu_usa_campus` SET `submission_value`='30' WHERE `zu_usa_campus_id`='76';");
        DB::statement("UPDATE `zu_usa_campus` SET `submission_value`='36' WHERE `zu_usa_campus_id`='75';");
        DB::statement("UPDATE `zu_usa_campus` SET `submission_value`='38' WHERE `zu_usa_campus_id`='79';");
        DB::statement("UPDATE `zu_usa_campus` SET `submission_value`='47' WHERE `zu_usa_campus_id`='77';");

        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='13', `campus`='16' WHERE `zu_usa_program_id`='745';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='87', `campus`='16' WHERE `zu_usa_program_id`='747';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='13', `campus`='38' WHERE `zu_usa_program_id`='749';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='128', `campus`='38' WHERE `zu_usa_program_id`='751';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='13', `campus`='47' WHERE `zu_usa_program_id`='741';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='114', `campus`='47' WHERE `zu_usa_program_id`='743';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='128', `campus`='47' WHERE `zu_usa_program_id`='744';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='13', `campus`='30' WHERE `zu_usa_program_id`='733';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='87', `campus`='30' WHERE `zu_usa_program_id`='735';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='98', `campus`='30' WHERE `zu_usa_program_id`='736';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='102', `campus`='30' WHERE `zu_usa_program_id`='737';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='107', `campus`='30' WHERE `zu_usa_program_id`='738';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='131', `campus`='30' WHERE `zu_usa_program_id`='739';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='13', `campus`='36' WHERE `zu_usa_program_id`='729';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='114', `campus`='36' WHERE `zu_usa_program_id`='731';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='128', `campus`='36' WHERE `zu_usa_program_id`='732';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='13', `campus`='29' WHERE `zu_usa_program_id`='725';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='114', `campus`='29' WHERE `zu_usa_program_id`='727';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='98', `campus`='29' WHERE `zu_usa_program_id`='728';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
