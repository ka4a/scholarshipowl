<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewZuusaSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("INSERT INTO `zu_usa_campaign` (`zu_usa_campaign_id`, `name`, `monthly_cap`, `active`, `submission_url`) VALUES (12, 'Daymar', '10', '1', 'http://sys.choosemydegree.com/ex-post/daymar/');");

        DB::statement("INSERT INTO `zu_usa_campus` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `zip`) VALUES ('9', '41', 'Amityville', '11701,11507,11702,11510,11709,11710,11714,11716,11717,11514,11721,11724,11726,11729,11554,11732,11735,11737,11010,11520,11599,11530,11531,11542,11545,11004,11547,11020,11026,11021,11027,11023,11022,11024,11739,11740,11548,11549,11551,11550,11557,11801,11802,11815,11819,11854,11743,11746,11558,11753,11853,11756,11757,11560,11563,11565,11030,11758,11762,11760,11775,11747,11566,11765,11501,11040,11042,11703,11770,11572,11804,11568,11771,11803,11051,11052,11053,11054,11055,11050,11570,11571,11575,11576,11577,11579,11783,11773,11791,11553,11555,11556,11580,11582,11581,11793,11704,11707,11552,11590,11596,11797,11798');");
        DB::statement("INSERT INTO `zu_usa_campus` (`zu_usa_campaign_id`, `submission_value`, `display_value`) VALUES ('1', 'MDL', 'Woodbridge, NJ');");
        DB::statement("INSERT INTO `zu_usa_campus` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `zip`) VALUES ('9', '46', 'Woodland Park', '07003,07004,07006,07009,07011,07012,07013,07014,07015,07026,07028,07031,07035,07042,07043,07044,07055,07057,07070,07073,07075,07110,07407,07410,07417,07424,07432,07440,07444,07446,07450,07451,07452,07470,07474,07501,07502,07503,07504,07505,07506,07507,07508,07509,07510,07511,07512,07513,07514,07522,07524,07533,07538,07543,07544,07602,07603,07604,07607,07608,07644,07652,07653,07661,07662,07663,07699');");
        DB::statement("INSERT INTO `zu_usa_campus` (`zu_usa_campaign_id`, `submission_value`, `display_value`) VALUES ('1', 'NYB', 'Brooklyn, NY');");
        DB::statement("INSERT INTO `zu_usa_campus` (`zu_usa_campaign_id`, `submission_value`, `display_value`) VALUES ('1', 'NYC', 'New York City, NY');");
        DB::statement("INSERT INTO `zu_usa_campus` (`zu_usa_campaign_id`, `submission_value`, `display_value`) VALUES ('1', 'WST', 'White Plains, NY');");

        DB::statement("INSERT INTO `zu_usa_campus` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `zip`) VALUES ('12', 'BG', 'Bowling Green', '37022,37048,37049,37119,37141,37148,37186,37188,42101,42102,42103,42104,42120,42122,42123,42127,42128,42130,42131,42133,42134,42135,42140,42141,42142,42153,42154,42156,42159,42160,42163,42164,42166,42170,42171,42201,42202,42204,42206,42207,42210,42219,42252,42256,42259,42261,42265,42273,42274,42275,42276,42280,42283,42285,42287,42288,42321,42323,42324,42326,42333,42337,42339,42349,42464,42721,42729,42749,42762');");
        DB::statement("INSERT INTO `zu_usa_campus` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `zip`) VALUES ('12', 'LC', 'Columbus', '43001,43004,43008,43011,43013,43021,43023,43025,43030,43031,43033,43046,43054,43055,43056,43062,43068,43069,43073,43074,43076,43080,43081,43082,43085,43102,43103,43105,43109,43110,43112,43113,43116,43117,43119,43123,43125,43130,43136,43137,43140,43143,43146,43147,43148,43154,43157,43162,43201,43202,43203,43204,43205,43206,43207,43209,43210,43211,43212,43213,43214,43215,43217,43219,43222,43223,43224,43227,43228,43229,43230,43231,43232,43240,43334');");
        DB::statement("INSERT INTO `zu_usa_campus` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `zip`) VALUES ('12', 'NP', 'Bellevue', '45102,45103,45140,45202,45205,45206,45207,45208,45211,45214,45219,45225,45229,45230,45231,45237,45238,45239,41001,41005,41011,41014,41015,41016,41017,41018,41019,41042,41048,41051,41071,41072,41073,41074,41075,41076,41077,41099');");
        DB::statement("INSERT INTO `zu_usa_campus` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `zip`) VALUES ('12', 'CV', 'Clarksville', '37010,37015,37023,37028,37029,37032,37035,37036,37040,37041,37042,37043,37044,37050,37051,37052,37056,37058,37061,37073,37079,37080,37101,37142,37146,37152,37165,37171,37175,37178,37181,37187,37188,37189,37191,42202,42204,42211,42216,42217,42220,42221,42223,42232,42234,42236,42240,42241,42254,42256,42262,42265,42266,42286');");
        DB::statement("INSERT INTO `zu_usa_campus` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `zip`) VALUES ('12', 'MB', 'Mufreesboro', '37011,37012,37013,37014,37016,37018,37019,37020,37024,37026,37027,37031,37034,37037,37046,37047,37059,37060,37062,37063,37064,37065,37067,37068,37069,37071,37085,37086,37087,37088,37089,37090,37091,37095,37118,37121,37127,37128,37129,37130,37131,37132,37133,37135,37136,37143,37144,37149,37153,37160,37161,37162,37166,37167,37174,37179,37180,37183,37184,37189,37190,37349,37352,37355,37360,37382');");
        DB::statement("INSERT INTO `zu_usa_campus` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `zip`) VALUES ('12', 'NV', 'Nashville', '37011,37013,37014,37022,37024,37027,37029,37031,37032,37037,37046,37048,37049,37056,37060,37062,37063,37064,37065,37066,37067,37068,37069,37070,37071,37072,37073,37075,37076,37077,37080,37082,37085,37086,37087,37090,37115,37116,37118,37119,37121,37122,37129,37130,37135,37136,37138,37141,37143,37148,37152,37153,37167,37179,37184,37186,37187,37188,37189,37201,37202,37203,37204,37205,37206,37207,37208,37209,37210,37211,37212,37213,37214,37215,37216,37217,37218,37219,37220,37221,37222,37224,37227,37228,37229,37230,37232,37234,37235,37236,37237,37238,37240,37241,37242,37243,37244,37245,37246,37247,37248,37249,37250');");
        DB::statement("INSERT INTO `zu_usa_campus` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `zip`) VALUES ('12', 'CVO', 'Clarksville Online', '30301,30302,30303,30304,30305,30306,30307,30308,30309,30310,30311,30312,30313,30314,30315,30316,30317,30318,30319,30320,30321,30322,30323,30324,30325,30326,30327,30328,30329,30330,30331,30332,30333,30334,30335,30336,30337,30338,30339,30340,30341,30342,30343,30344,30345,30346,30347,30348,30349,30350,30351,30352,30353,30354,30355,30356,30357,30358,30359,30360,30361,30362,30363,30364,30365,30366,30367,30368,30369,30370,30371,30372,30373,30374,30375,30376,30377,30378,30379,30380,30381,63101,63102,63103,63104,63105,63106,63107,63108,63109,63110,63111,63112,63113,63114,63115,63116,63117,63118,63119,63120,63121,63122,63123,63124,63125,63126,63127,63128,63129,63130,63131,63132,63133,63134,63135,63136,63137,63138,63139,63140,63141,39530,39531,39532,39533,39534,39535,37201,37202,37203,37204,37205,37206,37207,37208,37209,37210,37211,37212,37213,37214,37215,37216,37217,37218,37219,37220,37221,37222,25813');");
        DB::statement("INSERT INTO `zu_usa_campus` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `zip`) VALUES ('12', 'BGO', 'Bowling Green Online', '41701,41702');");

        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'BCS', 'Billing and Coding Specialist', 'BGO');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'BUSMGMT', 'Business Management', 'BGO');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'LEGAL', 'Criminal Justice', 'BGO');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'MA', 'Medical Assisting', 'BGO');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'PT', 'Pharmacy Technology', 'BGO');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'LEGAL', 'Criminal Justice', 'CVO');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'BCS', 'Billing and Coding Specialist', 'CVO');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'MA', 'Medical Assisting', 'CVO');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'PT', 'Pharmacy Technology', 'CVO');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'MST', 'Massage Therapy', 'NP');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'BCS', 'Billing & Coding Specialist', 'NP');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'BAT', 'Business Administration', 'NP');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'LEGAL', 'Criminal Justice', 'NP');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'MA', 'Medical Assisting', 'NP');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'BCS', 'Billing and Coding Specialist', 'BG');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'LEGAL ', 'Criminal Justice', 'BG');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'MA', 'Medical Assisting', 'BG');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'ACCTG', 'Accounting', 'BG');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'BUSMGMT', 'Business Management', 'BG');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'CT', 'Cardiographic Technician', 'BG');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'GRD', 'Graphic Design', 'BG');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'NSA', 'Networks Support Administrator', 'BG');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'PT', 'Pharmacy Technology', 'BG');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'ACCTG', 'Accounting', 'CV');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'BCS', 'Billing and Coding Specialist', 'CV');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'BUSMGMT', 'Business Management', 'CV');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'LEGAL', 'Criminal Justice', 'CV');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'DA', 'Dental Assisting', 'CV');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'HRM', 'Human Resource Management', 'CV');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'MA', 'Medical Assisting', 'CV');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'PT', 'Pharmacy Technology', 'CV');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'NSA', 'Networks Support Administrator', 'CV');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'BUSMGMT', 'Business Management', 'LC');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'MA', 'Medical Assisting', 'LC');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'MST', 'Massage Therapy', 'LC');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'LEGAL', 'Criminal Justice', 'LC');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'BCS', 'Billing and Coding Specialist', 'MB');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'DA', 'Dental Assisting', 'MB');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'MA', 'Medical Assisting', 'MB');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'PT', 'Pharmacy Technology', 'MB');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'BUSMGMT', 'Business Management', 'MB');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'LEGAL', 'Criminal Justice', 'MB');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'BCS', 'Billing & Coding Specialist', 'NV');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'BUSMGMT', 'Business Management', 'NV');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'LEGAL', 'Criminal Justice', 'NV');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'MST', 'Massage Therapy', 'NV');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'MA', 'Medical Assisting', 'NV');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'PT', 'Pharmacy Technology', 'NV');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'LEGAL', 'Criminal Justice', 'NV');");

        DB::statement("INSERT INTO `zu_usa_campus` (`zu_usa_campaign_id`, `submission_value`, `display_value`) VALUES ('6', '1', 'Any');");

        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'PHDBA-ACC', 'DBA Advanced Accounting', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'AAPHDB', 'DBA Advanced Accounting', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'AADBA', 'DBA Advanced Accounting', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ACSPHDB', 'DBA Applied Computer Science', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ACSDBA', 'DBA Applied Computer Science', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'CISPHDB', 'DBA Computer and Information Security', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'CISDBA', 'DBA Computer and Information Security', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'CJPHDB', 'DBA Criminal Justice', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'CJDBA', 'DBA Criminal Justice', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'DBA', 'DBA Doctor of Business Administration', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'PHD-BA', 'DBA Doctor of Philosophy in Business Administration', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'FMDBA', 'DBA Financial Management', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'FMPHDB', 'DBA Financial Management', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'GBDBA', 'DBA General Business', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'HCADBA', 'DBA Health Care Administration', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'HCAPHDB', 'DBA Health Care Administration', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'HSLPPHDB', 'DBA Homeland Security: Leadership and Policy', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'HSLPDBA', 'DBA Homeland Security: Leadership and Policy', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'HRMPHDB', 'DBA Human Resources Management', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'HRMDBA', 'DBA Human Resources Management', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'IOPDBA', 'DBA Industrial Organizational Psychology', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'IOPPHDB', 'DBA Industrial Organizational Psychology', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'IBDBA', 'DBA International Business', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'IBPHDB', 'DBA International Business', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'MGTDBA', 'DBA Management', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'MGTPHDB', 'DBA Management', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'MISPHDB', 'DBA Management Information Systems', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'MISDBA', 'DBA Management Information Systems', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'METDBA', 'DBA Management of Engineering & Technology', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'METPHDB', 'DBA Management of Engineering & Technology', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'MKTDBA', 'DBA Marketing', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'MKPHDB', 'DBA Marketing', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'MKTPHDB', 'DBA Marketing', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'OLDBA', 'DBA Organizational Leadership', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'OLPHDB', 'DBA Organizational Leadership', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'PMDBA', 'DBA Project Management', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'PMPHDB', 'DBA Project Management', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'PADBA', 'DBA Public Administration', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'PAPHDB', 'DBA Public Administration', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'SBDEMFTDMFT', 'DFMT Small Business Development and Entrepreneurship', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'CATMFTDMFT', 'DMFT Child & Adolescent Therapy', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'CTMFTDMFT', 'DMFT Couple Therapy', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'GMFTDMFT', 'DMFT General Family Therapy', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'MFTMFTDMFT', 'DMFT Medical Family Therapy', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'MHAMFTDMFT', 'DMFT Mental Health Administration', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'TMFMFTDMFT', 'DMFT Therapy with Military Families', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'DMFT', 'Doctorate of Marriage and Family Therapy', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'APHDPSY', 'DPSY Addictions', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'CHTMFTPHDPSY', 'DPSY Child and Adolescent Therapy', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'CTMFTPHDPSY', 'DPSY Couple Therapy', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'PHD-MFT', 'DPSY DOCTOR OF PHILOSOPHY IN MARRIAGE & FAMILY THERAPY', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'PHD-MPSY', 'DPSY Doctor of Philosophy in Marriage and Family Therapy', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'PHD-PSY', 'DPSY Doctor of Philosophy in Psychology', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'NCUPHDP', 'DPSY Doctor of Psychology', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'GSPHDPSY', 'DPSY Gender Studies', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'GMFTPHDPSY', 'DPSY General Family Therapy', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'GPSYPHDPSY', 'DPSY General Psychology', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'GPHDPSY', 'DPSY Gerontology', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'HPBMPHDPSY', 'DPSY Health Psychology/Behavioral Medicine', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'IOPPHDPSY', 'DPSY Industrial Organizational Psychology', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'MFTPHDPSY', 'DPSY Marriage and Family Therapy', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'MFTMFTPHDPSY', 'DPSY Medical Family Therapy', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'MHPPPHDPSY', 'DPSY PHILOSOPHY IN PSYCHOLOGY: Mental Health Policy and Practice', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'TMFMFTPHDPSY', 'DPSY Therapy with Military Families', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'TDRPHDPSY', 'DPSY Trauma and Disaster Relief', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'AAEDD', 'EDD Athletic Administration', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'AAEPHD', 'EDD Athletic Administration', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'CTEDD', 'EDD Curriculum and Teaching', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'CTEPHD', 'EDD Curriculum and Teaching', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'EdD-CT', 'EDD Curriculum and Teaching', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'EDD', 'EDD Doctor of Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'PHD-ED', 'EDD Doctor of Philosophy in Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ECHEPHD', 'EDD Early Childhood Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'CEEDD', 'EDD Early Childhood Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ETEEDD', 'EDD Education Technology and E- Learning', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ETEEPHD', 'EDD Education Technology and E- Learning', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'EdD-EDL', 'EDD Educational Leadership', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'EdD-EL', 'EDD Educational Leadership', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ELEPHD', 'EDD Educational Leadership', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ELEDD', 'EDD Educational Leadership', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'PHDED-EDL', 'EDD Educational Leadership', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ESLEPHD', 'EDD English as a Second Language (ESL)', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ESLEDD', 'EDD English as a Second Language (ESL)', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'GEEDD', 'EDD General Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'GTDEDD', 'EDD Global Training and Development', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'GTDEPHD', 'EDD Global Training and Development', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'HELEPHD', 'EDD Higher Education Leadership', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ILEPHD', 'EDD Instructional Leadership', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ILEDD', 'EDD Instructional Leadership', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'IEPHD', 'EDD International Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'IEEDD', 'EDD International Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'HELEDD', 'EDD Leadership in Higher Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'LAHEEDD', 'EDD Learning Analytics in Higher Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'LAEDD', 'EDD Learning Analytics K-12', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'NEEDD', 'EDD Nursing Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'OLEDD', 'EDD Organizational Leadership', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'OLEPHD', 'EDD Organizational Leadership', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'SEEPHD', 'EDD Special Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'SEEDD', 'EDD Special Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'SMEDD', 'EDD Sports Management', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'SMEPHD', 'EDD Sports Management', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'CTEDS', 'EDS Curriculum and Teaching', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ETEEDS', 'EDS E-Learning', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ECEEDS', 'EDS Early Childhood Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'EDS', 'EDS Education Specialist', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ELEDS', 'EDS Educational Leadership', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ESLEDS', 'EDS English Second Language', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'GEEDS', 'EDS General Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'GTDEDS', 'EDS Global Training and Development', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ILEDS', 'EDS Instructional Leadership', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'INTEDS', 'EDS International Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'HELEDS', 'EDS Leadership in Higher Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'LAHEEDS', 'EDS Learning Analytics in Higher Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'LAEDS', 'EDS Learning Analytics K-12', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'NEEDS', 'EDS Nursing Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'OLEDS', 'EDS Organizational Leadership', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'SEEDS', 'EDS Special Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'SMEDS', 'EDS Sports Management', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'PHD-OL', 'PhD Organizational Leadership', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'PHD-TIM', 'PhD Technology and Innovation Management', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'CSPHDTIM', 'PhD Technology and Innovation Management Computer Science', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'CYPHDTIM', 'PhD Technology and Innovation Management Cybersecurity', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'DSPHDTIM', 'PhD Technology and Innovation Management Data Science', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ISPHDTIM', 'PhD Technology and Innovation Management Information Systems', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'ITPMPHDTIM', 'PhD Technology and Innovation Management IT Project Management', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'GEEPHD', 'PHD-ED General Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'LAHEEPHD', 'PhD-ED Learning Analytics in Higher Education', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'LAEPHD', 'PhD-ED Learning Analytics K-12', '1');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('6', 'NEEPHD', 'PHD-ED Nursing Education', '1');");
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
