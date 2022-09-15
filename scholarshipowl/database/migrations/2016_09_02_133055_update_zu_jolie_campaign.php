<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateZuJolieCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE `zu_usa_campaign` SET `submission_url`='http://sys.choosemydegree.com/ex-post/jhb/' WHERE `zu_usa_campaign_id`='4';");
        
        DB::statement("UPDATE `zu_usa_campus` SET `submission_value`='JHBA-Cherry Hill', `zip`='08106,08007,08010,08015,08016,08101,08102,08103,08104,08105,08002,08003,08034,08108,08025,08518,08026,08030,08035,08033,08036,08048,08049,08051,08052,08053,08055,08109,08056,08057,08059,08060,08054,08061,08063,08107,08065,08110,08073,08075,08076,08077,08088,08043,08093,08046' WHERE `zu_usa_campus_id`='77';");
        DB::statement("UPDATE `zu_usa_campus` SET `submission_value`='JHBA-Hazelton', `zip`='17815,17901,17901,17921,17925,17929,17931,17932,17934,17935,17946,17948,17949,17952,17953,17954,17959,17960,17965,17967,17970,17972,17976,17982,17985,18201,18202,18211,18214,18216,18218,18219,18220,18221,18222,18223,18224,18225,18230,18231,18232,18234,18237,18239,18240,18241,18242,18245,18245,18246,18247,18248,18249,18250,18251,18252,18254,18255,18256,18601,18603,18617,18631,18635,18655,18660,18661,19549' WHERE `zu_usa_campus_id`='74';");
        DB::statement("UPDATE `zu_usa_campus` SET `submission_value`='JHBA-Ludlow', `zip`='01001,01002,01003,01004,01007,01009,01010,01013,01014,01020,01021,01022,01027,01028,01030,01031,01033,01035,01036,01037,01038,01039,01040,01041,01050,01053,01054,01056,01057,01059,01060,01061,01062,01063,01066,01069,01072,01073,01075,01080,01081,01082,01083,01085,01089,01090,01092,01093,01095,01101,01102,01103,01104,01105,01106,01107,01108,01109,01111,01115,01116,01118,01119,01128,01129,01138,01139,01144,01151,01152,01195,01199,01301,01360,01370,01375,01506,01531,01051,01342,01376,01518,01521,01545,01550,04280' WHERE `zu_usa_campus_id`='78';");
        DB::statement("UPDATE `zu_usa_campus` SET `submission_value`='JHBA-Northfield', `zip`='08001,08037,08087,08201,08201,08202,08203,08205,08205,08210,08213,08214,08215,08215,08217,08218,08220,08221,08223,08224,08225,08226,08230,08231,08232,08234,08234,08234,08239,08240,08241,08243,08244,08245,08246,08247,08248,08250,08270,08302,08302,08310,08314,08316,08317,08318,08319,08326,08330,08332,08340,08341,08342,08346,08348,08350,08360,08361,08362,08401,08402,08403,08404,08405,08406' WHERE `zu_usa_campus_id`='75';");
        DB::statement("UPDATE `zu_usa_campus` SET `submission_value`='JHBA-Turnersville', `zip`='08004,08009,08012,08014,08018,08020,08021,08025,08026,08027,08028,08029,08031,08032,08049,08051,08055,08056,08059,08061,08062,08063,08066,08067,08069,08071,08074,08078,08080,08081,08083,08084,08085,08086,08089,08090,08091,08094,08095,08096,08097,08099,08312,08318,08322,08326,08328,08332,08341,08343,08344,08346,08360,08045,08312' WHERE `zu_usa_campus_id`='79';");
        DB::statement("UPDATE `zu_usa_campus` SET `submission_value`='JHBA-Wilkes-Barre', `zip`='18224,18246,18347,18403,18407,18410,18411,18416,18434,18444,18447,18448,18452,18501,18502,18503,18504,18505,18507,18508,18509,18510,18512,18514,18515,18517,18518,18519,18522,18540,18577,18602,18602,18610,18612,18615,18617,18618,18621,18622,18624,18624,18625,18627,18634,18640,18641,18642,18643,18644,18651,18653,18654,18655,18661,18690,18701,18702,18703,18704,18705,18706,18707,18708,18709,18710,18711,18762,18764,18765,18766,18767,18769,18773,18825' WHERE `zu_usa_campus_id`='76';");

        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Cherry Hill' WHERE `zu_usa_program_id`='741';");
        DB::statement("DELETE FROM `zu_usa_program` WHERE `zu_usa_program_id`='742';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Cherry Hill' WHERE `zu_usa_program_id`='743';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Cherry Hill' WHERE `zu_usa_program_id`='744';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Hazelton' WHERE `zu_usa_program_id`='725';");
        DB::statement("DELETE FROM `zu_usa_program` WHERE `zu_usa_program_id`='726';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Hazelton' WHERE `zu_usa_program_id`='727';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Hazelton' WHERE `zu_usa_program_id`='728';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Ludlow' WHERE `zu_usa_program_id`='745';");
        DB::statement("DELETE FROM `zu_usa_program` WHERE `zu_usa_program_id`='746';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Ludlow' WHERE `zu_usa_program_id`='747';");
        DB::statement("DELETE FROM `zu_usa_program` WHERE `zu_usa_program_id`='748';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Northfield' WHERE `zu_usa_program_id`='729';");
        DB::statement("DELETE FROM `zu_usa_program` WHERE `zu_usa_program_id`='730';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Northfield' WHERE `zu_usa_program_id`='731';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Northfield' WHERE `zu_usa_program_id`='732';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Turnersville' WHERE `zu_usa_program_id`='749';");
        DB::statement("DELETE FROM `zu_usa_program` WHERE `zu_usa_program_id`='750';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Turnersville' WHERE `zu_usa_program_id`='751';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Wilkes-Barre' WHERE `zu_usa_program_id`='733';");
        DB::statement("DELETE FROM `zu_usa_program` WHERE `zu_usa_program_id`='734';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Wilkes-Barre' WHERE `zu_usa_program_id`='735';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Wilkes-Barre' WHERE `zu_usa_program_id`='736';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Wilkes-Barre' WHERE `zu_usa_program_id`='737';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Wilkes-Barre' WHERE `zu_usa_program_id`='738';");
        DB::statement("UPDATE `zu_usa_program` SET `campus`='JHBA-Wilkes-Barre' WHERE `zu_usa_program_id`='739';");
        DB::statement("DELETE FROM `zu_usa_program` WHERE `zu_usa_program_id`='740';");

        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='Barbering' WHERE `zu_usa_program_id`='731';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='Skin Care Specialty' WHERE `zu_usa_program_id`='732';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='Cosmetology' WHERE `zu_usa_program_id`='733';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='Esthetician' WHERE `zu_usa_program_id`='735';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='Nail Technology' WHERE `zu_usa_program_id`='736';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='Patient Care Technician' WHERE `zu_usa_program_id`='737';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='Phlebotomy' WHERE `zu_usa_program_id`='738';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='Nurse Aide' WHERE `zu_usa_program_id`='739';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='Cosmetology' WHERE `zu_usa_program_id`='741';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='Barbering' WHERE `zu_usa_program_id`='743';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='Skin Care Specialty' WHERE `zu_usa_program_id`='744';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='Cosmetology' WHERE `zu_usa_program_id`='745';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='Esthetician' WHERE `zu_usa_program_id`='747';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='Cosmetology' WHERE `zu_usa_program_id`='749';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='Skin Care Specialty' WHERE `zu_usa_program_id`='751';");
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
