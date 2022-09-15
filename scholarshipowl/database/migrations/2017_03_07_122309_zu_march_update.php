<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ZuMarchUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE `zu_usa_campaign` SET `name`='Ancora Education Platt', `submission_url`='https://classic.leadconduit.com/v2/PostLeadAction', `submission_value`='05ai19l3e' WHERE `zu_usa_campaign_id`='15';");
        DB::statement("INSERT INTO `zu_usa_campaign` (`zu_usa_campaign_id`, `name`, `monthly_cap`, `active`, `submission_url`, `submission_value`) VALUES ('21', 'Ancora Education STVT', '100', '1', 'https://classic.leadconduit.com/v2/PostLeadAction', '05ai19l3f');");
        DB::statement("UPDATE `zu_usa_campaign` SET `active`='1', `submission_url`='https://classic.leadconduit.com/v2/PostLeadAction', `submission_value`='05ai19l2x' WHERE `zu_usa_campaign_id`='12';");
        DB::statement("UPDATE `zu_usa_campaign` SET `active`='1', `submission_url`='https://classic.leadconduit.com/v2/PostLeadAction', `submission_value`='05ai19l2q' WHERE `zu_usa_campaign_id`='16';");
        DB::statement("UPDATE `zu_usa_campaign` SET `submission_url`='https://classic.leadconduit.com/v2/PostLeadAction', `submission_value`='05aj2jyct' WHERE `zu_usa_campaign_id`='14';");
        DB::statement("UPDATE `zu_usa_campaign` SET `active`='0' WHERE `zu_usa_campaign_id`='13';");
        DB::statement("UPDATE `zu_usa_campaign` SET `active`='0' WHERE `zu_usa_campaign_id`='17';");
        DB::statement("UPDATE `zu_usa_campaign` SET `active`='0' WHERE `zu_usa_campaign_id`='18';");
        DB::statement("UPDATE `zu_usa_campaign` SET `active`='0' WHERE `zu_usa_campaign_id`='19';");
        DB::statement("UPDATE `zu_usa_campaign` SET `active`='0' WHERE `zu_usa_campaign_id`='20';");
        DB::statement("UPDATE `zu_usa_campaign` SET `active`='0' WHERE `zu_usa_campaign_id`='3';");

        DB::statement("UPDATE `zu_usa_campus` SET `zip`='73055,73501,73502,73503,73505,73507,73521,73527,73529,73533,73540,73541,73548,73549,73566', `monthly_cap`='25' WHERE `zu_usa_campus_id`='149';");
        DB::statement("UPDATE `zu_usa_campus` SET `zip`='74008,74012,74014,74017,74021,74047,74055,74063,74066,74105,74106,74107,74110,74116,74119,74120,74126,74127,74128,74132,74133,74136,74137,74145,74146,74429,74436,74447,74467', `monthly_cap`='25' WHERE `zu_usa_campus_id`='153';");
        DB::statement("UPDATE `zu_usa_campus` SET `display_value`='Oklahoma City, OK (Central)', `zip`='73012,73013,73034,73036,73045,73065,73069,73071,73101,73105,73107,73110,73111,73114,73115,73116,73117,73119,73120,73122,73127,73128,73129,73131,73132,73134,73135,73141,73142,73146,73148,73149,73156,73160,73165,73170,73179,73502,73503,73507', `monthly_cap`='1' WHERE `zu_usa_campus_id`='151';");
        DB::statement("UPDATE `zu_usa_campus` SET `zip`='73010,73013,73065,73068,73069,73071,73080,73089,73101,73105,73106,73107,73110,73111,73114,73115,73116,73117,73119,73120,73122,73127,73128,73129,73131,73134,73135,73141,73142,73146,73148,73149,73156,73160,73165,73170,73179,74873', `monthly_cap`='1' WHERE `zu_usa_campus_id`='150';");
        DB::statement("UPDATE `zu_usa_campus` SET `display_value`='Oklahoma City, OK (North)', `zip`='73012,73013,73028,73034,73036,73044,73045,73049,73065,73069,73071,73089,73101,73106,73107,73110,73111,73114,73115,73116,73117,73119,73120,73122,73127,73128,73129,73131,73132,73134,73135,73141,73142,73146,73148,73149,73156,73160,73165,73170,73179,74857', `monthly_cap`='1' WHERE `zu_usa_campus_id`='152';");
        DB::statement("UPDATE `zu_usa_campus` SET `zu_usa_campaign_id`='21', `zip`='78501,78503,78537,78538,78543,78549,78557,78558,78560,78572,78573,78574,78576,78582,78584,78589,78595', `monthly_cap`='1' WHERE `zu_usa_campus_id`='156';");
        DB::statement("UPDATE `zu_usa_campus` SET `zu_usa_campaign_id`='21', `zip`='78516,78537,78538,78539,78543,78549,78550,78552,78558,78559,78570,78579,78580,78589,78592,78593,78596,78599,78542', `monthly_cap`='2' WHERE `zu_usa_campus_id`='158';");
        DB::statement("UPDATE `zu_usa_campus` SET `zu_usa_campaign_id`='21', `zip`='78520,78521,78526,78559,78570,78578,78579,78580,78592,78593,78596', `monthly_cap`='25' WHERE `zu_usa_campus_id`='154';");
        DB::statement("UPDATE `zu_usa_campus` SET `zip`='37042,37075,37086,37122,37130,37138,37184,37189,37203,37211,37214,37217,37218,37219,37813,38261,38501,38828', `monthly_cap`='1' WHERE `zu_usa_campus_id`='131';");
        DB::statement("UPDATE `zu_usa_campus` SET `zip`='37022,37049,37066,37148,41234,42101,42120,42133,42141,42171,42202,42206,42274,42323,42337,42762', `monthly_cap`='1' WHERE `zu_usa_campus_id`='126';");
        DB::statement("UPDATE `zu_usa_campus` SET `is_active`='0' WHERE `zu_usa_campus_id`='133';");
        DB::statement("UPDATE `zu_usa_campus` SET `zip`='43004,43021,43054,43102,43103,43110,43112,43137,43143,43202,43207,43212,43224,43232', `monthly_cap`='1' WHERE `zu_usa_campus_id`='127';");
        DB::statement("UPDATE `zu_usa_campus` SET `is_active`='0' WHERE `zu_usa_campus_id`='132';");
        DB::statement("UPDATE `zu_usa_campus` SET `zip`='37040,37042,37043,37052,37055,37142,37178,37191,42204,42223,42234,42262,42266', `monthly_cap`='1' WHERE `zu_usa_campus_id`='129';");
        DB::statement("UPDATE `zu_usa_campus` SET `zip`='37018,37026,37037,37046,37047,37067,37110,37127,37129,37130,37144,37149,37153,37162,37183,37206,37342,37357,37360,37388,38016,38401,38555', `monthly_cap`='1' WHERE `zu_usa_campus_id`='130';");
        DB::statement("UPDATE `zu_usa_campus` SET `zip`='30002,30003,30004,30005,30006,30007,30008,30009,30010,30011,30012,30013,30017,30019,30021,30022,30023,30024,30026,30028,30029,30030,30031,30032,30033,30034,30035,30036,30037,30038,30039,30040,30041,30042,30043,30044,30045,30046,30047,30048,30052,30058,30060,30061,30062,30063,30064,30065,30066,30067,30068,30069,30071,30072,30074,30075,30076,30077,30078,30079,30080,30081,30082,30083,30084,30085,30086,30087,30088,30090,30091,30092,30093,30094,30095,30096,30097,30098,30099,30101,30102,30106,30107,30111,30114,30115,30122,30126,30127,30133,30134,30135,30137,30141,30142,30144,30146,30152,30168,30188,30189,30213,30236,30237,30260,30272,30273,30274,30281,30287,30288,30291,30294,30296,30297,30298,30301,30302,30303,30304,30305,30306,30307,30308,30309,30310,30311,30312,30313,30314,30315,30316,30317,30318,30319,30320,30321,30322,30324,30325,30326,30327,30328,30329,30331,30332,30333,30334,30336,30337,30338,30339,30340,30341,30342,30343,30344,30345,30346,30348,30349,30350,30353,30354,30355,30356,30357,30358,30359,30360,30361,30362,30363,30364,30366,30368,30369,30370,30371,30374,30375,30377,30378,30380,30384,30385,30388,30392,30394,30396,30398,30515,30518,30519,30542,31106,31107,31119,31126,31131,31139,31141,31145,31146,31150,31156,31192,31193,31195,31196,39901,30049,30156,30160,30169,31136,30338', `monthly_cap`='1' WHERE `zu_usa_campus_id`='177';");
        DB::statement("UPDATE `zu_usa_campus` SET `zip`='27009,27025,27027,27042,27048,27051,27052,27101,27102,27105,27107,27108,27109,27110,27111,27113,27114,27115,27116,27117,27120,27130,27150,27152,27155,27157,27198,27199,27201,27202,27203,27204,27214,27215,27216,27217,27230,27233,27235,27244,27248,27249,27253,27258,27260,27261,27262,27263,27264,27265,27282,27283,27284,27285,27288,27289,27298,27301,27302,27310,27313,27316,27317,27320,27323,27326,27340,27342,27349,27350,27355,27357,27358,27359,27360,27361,27370,27373,27375,27377,27401,27402,27403,27404,27405,27406,27407,27408,27409,27410,27411,27412,27413,27415,27416,27417,27419,27420,27425,27427,27429,27435,27438,27455,27495,27498,27499,27497', `monthly_cap`='1' WHERE `zu_usa_campus_id`='178';");
        DB::statement("UPDATE `zu_usa_campus` SET `zip`='29801,29803,29816,29821,29822,29828,29829,29831,29834,29838,29841,29842,29847,29850,29851,29860,29861,30442,30456,30477,30673,30802,30805,30806,30808,30809,30812,30813,30814,30815,30816,30817,30818,30824,30830,30833,30901,30903,30904,30905,30906,30907,30909,30912,30914,30916,30917,30919,30999', `monthly_cap`='1' WHERE `zu_usa_campus_id`='159';");
        DB::statement("UPDATE `zu_usa_campus` SET `zip`='08002,08003,08004,08007,08009,08010,08011,08012,08014,08015,08016,08018,08020,08021,08022,08025,08026,08027,08028,08029,08030,08031,08032,08033,08034,08035,08036,08037,08039,08041,08042,08043,08045,08046,08048,08049,08051,08052,08053,08054,08055,08056,08057,08059,08060,08061,08062,08063,08064,08065,08066,08067,08068,08069,08071,08073,08074,08075,08076,08077,08078,08080,08081,08083,08084,08085,08086,08088,08089,08090,08091,08093,08094,08095,08096,08097,08098,08099,08101,08102,08103,08104,08105,08106,08107,08108,08109,08110,08217,08310,08312,08318,08322,08326,08328,08341,08343,08344,08346,08501,08505,08511,08515,08518,08530,08533,08534,08554,08560,08562,08601,08602,08603,08604,08605,08606,08607,08608,08609,08610,08611,08618,08619,08620,08625,08628,08629,08638,08640,08641,08645,08646,08647,08648,08650,08666,08690,08695,18954,18966,18974,18991,19001,19002,19003,19004,19006,19007,19008,19009,19010,19012,19016,19018,19019,19020,19021,19022,19023,19025,19026,19027,19029,19030,19031,19032,19033,19034,19035,19036,19037,19038,19041,19043,19044,19046,19047,19048,19049,19050,19053,19054,19055,19056,19057,19058,19064,19065,19066,19070,19072,19074,19075,19076,19078,19079,19081,19082,19083,19085,19086,19090,19091,19092,19093,19094,19095,19096,19098,19099,19101,19102,19103,19104,19105,19106,19107,19108,19109,19110,19111,19112,19113,19114,19115,19116,19118,19119,19120,19121,19122,19123,19124,19125,19126,19127,19128,19129,19130,19131,19132,19133,19134,19135,19136,19137,19138,19139,19140,19141,19142,19143,19144,19145,19146,19147,19148,19149,19150,19151,19152,19153,19154,19155,19160,19161,19162,19170,19171,19172,19173,19175,19177,19178,19179,19181,19182,19183,19184,19185,19187,19188,19191,19192,19193,19194,19196,19197,19244,19255,19424,19428,19429,19176,19195,19190,19194,19040', `monthly_cap`='1' WHERE `zu_usa_campus_id`='174';");
        DB::statement("UPDATE `zu_usa_campus` SET `zip`='01432,01451,01460,01503,01532,01581,01701,01702,01703,01704,01705,01718,01719,01720,01721,01730,01731,01740,01741,01742,01745,01746,01747,01748,01749,01752,01754,01757,01760,01770,01772,01773,01775,01776,01778,01784,01801,01803,01805,01810,01812,01813,01815,01821,01822,01824,01826,01831,01833,01834,01835,01840,01841,01842,01843,01844,01845,01850,01851,01852,01853,01854,01862,01863,01864,01865,01866,01867,01876,01879,01880,01885,01886,01887,01888,01889,01890,01899,01901,01902,01903,01904,01905,01906,01907,01908,01910,01915,01921,01922,01923,01929,01930,01931,01936,01937,01938,01940,01944,01945,01949,01951,01960,01961,01965,01969,01970,01971,01982,01983,01984,02018,02019,02020,02021,02025,02026,02027,02030,02032,02035,02038,02040,02041,02043,02044,02045,02047,02048,02050,02051,02052,02053,02054,02055,02056,02059,02060,02061,02062,02065,02066,02067,02070,02071,02072,02081,02090,02093,02108,02109,02110,02111,02112,02113,02114,02115,02116,02117,02118,02119,02120,02121,02122,02123,02124,02125,02126,02127,02128,02129,02130,02131,02132,02133,02134,02135,02136,02137,02138,02139,02140,02141,02142,02143,02144,02145,02148,02149,02150,02151,02152,02153,02155,02156,02163,02169,02170,02171,02176,02180,02184,02185,02186,02187,02188,02189,02190,02191,02196,02199,02201,02203,02204,02205,02206,02210,02211,02212,02215,02217,02222,02238,02241,02266,02269,02283,02284,02293,02297,02301,02302,02303,02304,02305,02322,02324,02325,02327,02331,02332,02333,02334,02337,02338,02339,02341,02343,02350,02351,02356,02357,02358,02359,02367,02368,02370,02375,02379,02382,02420,02421,02445,02446,02447,02451,02452,02453,02454,02456,02457,02458,02459,02460,02461,02462,02464,02465,02466,02467,02468,02471,02472,02474,02475,02476,02477,02478,02479,02481,02482,02492,02493,02494,02495,02712,02761,02762,02763,02766,02767,02768,05501,05544,02340,02455,02228,02298', `monthly_cap`='1' WHERE `zu_usa_campus_id`='166';");
        DB::statement("UPDATE `zu_usa_campus` SET `zip`='27013,27054,28001,28002,28006,28010,28012,28023,28025,28026,28027,28031,28032,28034,28036,28037,28039,28041,28053,28054,28055,28056,28070,28071,28072,28075,28077,28078,28079,28080,28081,28082,28083,28088,28097,28098,28101,28104,28105,28106,28107,28109,28110,28111,28115,28117,28120,28123,28124,28125,28126,28127,28129,28130,28134,28137,28138,28144,28145,28146,28147,28159,28163,28164,28166,28201,28202,28203,28204,28205,28206,28207,28208,28209,28210,28211,28212,28213,28214,28215,28216,28217,28218,28219,28220,28221,28222,28223,28224,28226,28227,28228,28229,28230,28231,28232,28233,28234,28235,28236,28237,28241,28242,28243,28244,28246,28247,28250,28253,28254,28255,28256,28258,28260,28262,28265,28266,28269,28270,28272,28273,28274,28275,28277,28278,28280,28281,28282,28284,28285,28287,28288,28289,28290,28296,28297,28299,28609,28650,28673,28677,28682,28687,28271,28035,28263', `monthly_cap`='1' WHERE `zu_usa_campus_id`='176';");
        DB::statement("UPDATE `zu_usa_campus` SET `zip`='83220,83223,83228,83232,83234,83237,83252,83254,83261,83263,83272,83283,83286,83287,84003,84004,84011,84013,84014,84015,84016,84020,84025,84028,84029,84032,84037,84038,84040,84041,84042,84043,84044,84047,84049,84050,84054,84056,84057,84058,84059,84062,84064,84065,84067,84070,84074,84075,84082,84084,84086,84087,84088,84089,84090,84091,84092,84093,84094,84097,84098,84101,84102,84103,84104,84105,84106,84107,84108,84109,84110,84111,84112,84113,84114,84115,84116,84117,84118,84119,84120,84121,84123,84124,84128,84135,84136,84137,84138,84139,84140,84141,84142,84143,84144,84145,84147,84148,84150,84151,84152,84153,84157,84158,84165,84170,84171,84180,84184,84185,84189,84190,84199,84201,84244,84301,84302,84304,84305,84306,84307,84308,84309,84310,84311,84312,84314,84315,84316,84317,84318,84319,84320,84321,84322,84323,84324,84325,84326,84327,84328,84330,84331,84332,84333,84334,84335,84336,84337,84338,84339,84340,84341,84401,84402,84403,84404,84405,84407,84409,84412,84414,84415,84601,84602,84603,84604,84605,84606,84626,84633,84651,84653,84655,84660,84663,84664,84096,84005,84045,84081,84129', `monthly_cap`='5' WHERE `zu_usa_campus_id`='144';");
        DB::statement("UPDATE `zu_usa_campus` SET `zip`='83220,83223,83228,83232,83234,83237,83252,83254,83261,83263,83272,83283,83286,83287,84003,84004,84011,84013,84014,84015,84016,84020,84025,84028,84029,84032,84037,84038,84040,84041,84042,84043,84044,84047,84049,84050,84054,84056,84057,84058,84059,84062,84064,84065,84067,84070,84074,84075,84082,84084,84086,84087,84088,84089,84090,84091,84092,84093,84094,84097,84098,84101,84102,84103,84104,84105,84106,84107,84108,84109,84110,84111,84112,84113,84114,84115,84116,84117,84118,84119,84120,84121,84123,84124,84128,84135,84136,84137,84138,84139,84140,84141,84142,84143,84144,84145,84147,84148,84150,84151,84152,84153,84157,84158,84165,84170,84171,84180,84184,84185,84189,84190,84199,84201,84244,84301,84302,84304,84305,84306,84307,84308,84309,84310,84311,84312,84314,84315,84316,84317,84318,84319,84320,84321,84322,84323,84324,84325,84326,84327,84328,84330,84331,84332,84333,84334,84335,84336,84337,84338,84339,84340,84341,84401,84402,84403,84404,84405,84407,84409,84412,84414,84415,84601,84602,84603,84604,84605,84606,84626,84633,84651,84653,84655,84660,84663,84664,84096,84005,84045,84081,84129', `is_active`='1', `monthly_cap`='5' WHERE `zu_usa_campus_id`='145';");
        DB::statement("UPDATE `zu_usa_campus` SET `is_active`='1', `monthly_cap`='2' WHERE `zu_usa_campus_id`='140';");
        DB::statement("UPDATE `zu_usa_campus` SET `is_active`='1', `monthly_cap`='5' WHERE `zu_usa_campus_id`='146';");
        DB::statement("UPDATE `zu_usa_campus` SET `is_active`='1', `monthly_cap`='1' WHERE `zu_usa_campus_id`='141';");
        DB::statement("UPDATE `zu_usa_campus` SET `monthly_cap`='1' WHERE `zu_usa_campus_id`='147';");
        DB::statement("UPDATE `zu_usa_campus` SET `is_active`='1', `monthly_cap`='1' WHERE `zu_usa_campus_id`='143';");

        DB::statement("UPDATE `zu_usa_program` SET `zip`='00000' WHERE `zu_usa_program_id`='1572';");
        DB::statement("UPDATE `zu_usa_program` SET `display_value`='Medical Assistant Phlebotomy' WHERE `zu_usa_program_id`='1574';");
        DB::statement("DELETE FROM `zu_usa_program` WHERE `zu_usa_program_id`='1584';");
        DB::statement("UPDATE `zu_usa_program` SET `zip`='' WHERE `zu_usa_program_id`='1579';");
        DB::statement("UPDATE `zu_usa_program` SET `zip`='' WHERE `zu_usa_program_id`='1580';");
        DB::statement("UPDATE `zu_usa_program` SET `display_value`='Practical Nursing AOS LPN', `zip`='' WHERE `zu_usa_program_id`='1578';");
        DB::statement("UPDATE `zu_usa_program` SET `display_value`='Medical Assistant Phlebotomy', `zip`='' WHERE `zu_usa_program_id`='1576';");
        DB::statement("UPDATE `zu_usa_program` SET `zip`='' WHERE `zu_usa_program_id`='1575';");
        DB::statement("UPDATE `zu_usa_program` SET `zip`='' WHERE `zu_usa_program_id`='1577';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='Culinary Arts - AS', `zip`='' WHERE `zu_usa_program_id`='1581';");
        DB::statement("UPDATE `zu_usa_program` SET `zip`='' WHERE `zu_usa_program_id`='1582';");
        DB::statement("UPDATE `zu_usa_program` SET `zu_usa_campaign_id`='21' WHERE `zu_usa_program_id`='1589';");
        DB::statement("UPDATE `zu_usa_program` SET `zu_usa_campaign_id`='21' WHERE `zu_usa_program_id`='1592';");
        DB::statement("UPDATE `zu_usa_program` SET `zu_usa_campaign_id`='21' WHERE `zu_usa_program_id`='1593';");
        DB::statement("UPDATE `zu_usa_program` SET `zu_usa_campaign_id`='21' WHERE `zu_usa_program_id`='1586';");
        DB::statement("DELETE FROM `zu_usa_program` WHERE `zu_usa_program_id`='1110';");
        DB::statement("DELETE FROM `zu_usa_program` WHERE `zu_usa_program_id`='1113';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='HCA', `display_value`='Health Care Administration' WHERE `zu_usa_program_id`='1115';");
        DB::statement("DELETE FROM `zu_usa_program` WHERE `zu_usa_program_id`='1116';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='LEGAL', `display_value`='Criminal Justice' WHERE `zu_usa_program_id`='1088';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='BUSMGMT', `display_value`='Business Management' WHERE `zu_usa_program_id`='1089';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='MA', `display_value`='Medical Assisting' WHERE `zu_usa_program_id`='1090';");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'CT', 'Cardiographic Technology', 'BG');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('12', 'PT', 'Pharmacy Technology', 'BG');");
        DB::statement("DELETE FROM `zu_usa_program` WHERE `zu_usa_program_id`='1099';");
        DB::statement("DELETE FROM `zu_usa_program` WHERE `zu_usa_program_id`='1092';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='ACCTG', `display_value`='Accounting' WHERE `zu_usa_program_id`='1104';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='LEGAL', `display_value`='Criminal Justice' WHERE `zu_usa_program_id`='1105';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='DA', `display_value`='Dental Assisting' WHERE `zu_usa_program_id`='1106';");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='MA', `display_value`='Medical Assisting' WHERE `zu_usa_program_id`='1109';");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('16', 'teacher', 'Cosmetology Instructor', 'empire-dunwoody-ga');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('16', 'cosmetology', 'Cosmetology', 'empire-east-greensboro-nc');");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='teacher' WHERE `zu_usa_program_id`='1615';");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('16', 'Cosmetology - Teacher Training', 'Cosmetology - Teacher Training', 'empire-augusta-ga');");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='teacher' WHERE `zu_usa_program_id`='1611';");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('16', 'cosmetology', 'Cosmetology', 'empire-cherry-hill-nj');");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('16', 'Cosmetology - Teacher Training', 'Cosmetology - Teacher Training', 'empire-boston-ma');");
        DB::statement("UPDATE `zu_usa_program` SET `submission_value`='teacher' WHERE `zu_usa_program_id`='1613';");
        DB::statement("INSERT INTO `zu_usa_program` (`zu_usa_campaign_id`, `submission_value`, `display_value`, `campus`) VALUES ('16', 'cosmetology', 'Cosmetology', 'empire-concord-nc');");
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
