<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoublePositiveProgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("double_positive_program",function (Blueprint $table){
            $table->increments("id");
            $table->integer("degree_type_id",false,true);
            $table->string("program");
            $table->text("states");
            $table->integer("min_hs_grad_year",false,true);
            $table->integer("max_hs_grad_year",false,true);
            $table->boolean("is_active")->default(true);
        });

        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('3','Accounting','AZ,FL,GA,ID,IL,IN,KS,KY,LA,MI,MS,MO,NH,NJ,NY,OH,OK,SC,TN,TX,VA,WA,WV,AL,AK,CA,CO,CT,DE,HI,ME,NE,NV,NM,ND,OR,PA,RI,SD,UT,VT,WI,WY','1975','2016');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('3','Accounting','AR,MD,MT,NC','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('3','Biblical Studies','AL,AK,AZ,AR,CA,CO,CT,DE,DC,FL,GA,HI,ID,IL,IN,IA,KS,KY,LA,ME,MD,MA,MI,MN,MO,MT,NE,NV,NH,NJ,NM,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('3','Business Administration','MS','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('3','Healthcare Management','AL,AK,AZ,AR,CA,CO,DE,DC,FL,GA,HI,ID,IL,IN,IA,KY,LA,ME,MD,MI,MN,MS,MO,MT,NE,NV,NH,NJ,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('3','Human Services','AL,AK,AZ,AR,CA,CO,DE,DC,FL,GA,HI,ID,IL,IN,IA,KY,ME,MD,MI,MS,MO,MT,NE,NV,NH,NJ,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,WA,WV,WI,WY','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('3','Information Systems and Security','AZ,AR,FL,GA,ID,IL,IN,KS,KY,LA,MD,MI,MS,MO,MT,NH,NJ,NY,NC,OH,OK,SC,TN,TX,VA,WA,WV','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('3','Medical Billing and Coding','AL,AK,AZ,AR,CA,CO,DE,DC,FL,GA,HI,ID,IL,IN,IA,KY,LA,ME,MD,MI,MN,MS,MO,MT,NE,NV,NH,NJ,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('3','Business Administration','AL,AK,AZ,AR,CA,CO,CT,DE,DC,FL,GA,HI,ID,IL,IN,IA,KS,KY,LA,ME,MD,MA,MI,MN,MO,MT,NE,NV,NH,NJ,NM,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('3','Management and Leadership','AZ,AR,FL,GA,ID,IL,IN,KS,KY,LA,MD,MI,MS,MO,MT,NH,NJ,NY,NC,OH,OK,SC,TN,TX,VA,WA,WV','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Accounting','AL','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Accounting','AK,CA,CO,CT,DE,DC,HI,IA,ME,MA,MN,NE,NV,NM,ND,OR,PA,RI,SD,UT,VT,WI,WY','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Business Administration','AL,AK,AZ,AR,CA,CO,CT,DE,DC,FL,GA,HI,ID,IL,IN,IA,KS,KY,LA,ME,MD,MA,MI,MN,MO,MT,NE,NV,NH,NJ,NM,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Business Administration','AL,AK,AZ,CA,CO,CT,DE,FL,GA,HI,ID,IL,IN,KS,KY,LA,ME,MI,MS,MO,NE,NV,NH,NJ,NM,NY,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY,DC,IA,MD,MT,NC','1975','2016');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Business Administration','AL,AK,AZ,CA,CO,CT,DE,DC,FL,GA,HI,ID,IL,IN,IA,KS,KY,LA,ME,MD,MA,MI,MN,MS,MO,MT,NE,NV,NH,NJ,NM,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2017');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Clinical Psychology','AL,AK,AZ,CA,CO,CT,DE,DC,FL,GA,HI,ID,IL,IN,IA,KS,KY,LA,ME,MD,MA,MI,MN,MS,MO,MT,NE,NV,NH,NJ,NM,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Criminal Justice','AL,AK,AZ,AR,CA,CO,CT,DE,DC,FL,GA,HI,ID,IL,IN,IA,KS,KY,LA,ME,MD,MA,MI,MN,MO,MT,NE,NV,NH,NJ,NM,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY,MS','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Forensic Accounting','AL,AK,AZ,CA,CO,CT,DE,FL,GA,HI,ID,IL,IN,KS,KY,LA,ME,MI,MS,MO,NE,NV,NH,NJ,NM,NY,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2016');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Forensic Psychology','AL,AK,AZ,CA,CO,CT,DE,DC,FL,GA,HI,ID,IL,IN,IA,KS,KY,LA,ME,MD,MA,MI,MN,MS,MO,MT,NE,NV,NH,NJ,NM,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Healthcare Management','CT,KS,MA,NM','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Human Resources','AL,AK,AZ,CA,CO,CT,DE,FL,GA,HI,ID,IL,IN,KS,KY,LA,ME,MI,MS,MO,NE,NV,NH,NJ,NM,NY,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2016');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Information Systems and Security','AL,AK,CA,CO,CT,DE,DC,HI,IA,ME,MA,MN,NE,NV,NM,ND,OR,PA,RI,SD,UT,VT,WI,WY','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Information Technology','AK,AZ,CA,CO,CT,DE,DC,FL,GA,ID,IL,IN,IA,KS,KY,LA,ME,MD,MI,MS,MO,MT,NE,NV,NH,NJ,NM,NY,NC,ND,OH,OK,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2016');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Liberal Arts','AK,AZ,CA,CO,CT,DE,DC,FL,GA,ID,IL,IN,IA,KS,KY,LA,ME,MD,MI,MS,MO,MT,NE,NV,NH,NJ,NM,NY,NC,ND,OH,OK,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2016');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Management and Leadership','AZ,AR,FL,GA,ID,IL,IN,KS,KY,LA,MD,MI,MS,MO,MT,NH,NJ,NY,NC,OH,OK,SC,TN,TX,VA,WA,WV','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Management and Leadership','AL,AK,CA,CO,CT,DE,DC,HI,IA,ME,MA,MN,NE,NV,NM,ND,OR,PA,RI,SD,UT,VT,WI,WY','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Organizational Psychology','AL,AK,AZ,CA,CO,CT,DE,DC,FL,GA,ID,IL,IN,IA,KS,KY,LA,ME,MD,MI,MN,MS,MO,MT,NE,NV,NH,NJ,NM,NY,NC,ND,OH,OK,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2016');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Organizational Psychology','HI,MA,OR','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Property Management','AL,AK,AZ,CA,CO,CT,DE,FL,GA,HI,ID,IL,IN,KS,KY,LA,ME,MI,MS,MO,NE,NV,NH,NJ,NM,NY,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2016');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Psychology','AL,AK,AZ,CA,CO,CT,DE,DC,FL,GA,ID,IL,IN,IA,KS,KY,LA,ME,MD,MI,MN,MO,MT,NE,NV,NH,NJ,NM,NY,NC,ND,OH,OK,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY,MS','1975','2016');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Psychology','AR,HI,MA,OR','1975','2016');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Substance Abuse Counseling','AL,AK,AZ,AR,CA,CO,CT,DE,DC,FL,GA,HI,ID,IL,IN,IA,KS,KY,LA,ME,MD,MA,MI,MN,MO,MT,NE,NV,NH,NJ,NM,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Web Development','AL,AK,AZ,CA,CO,CT,DE,FL,GA,HI,ID,IL,IN,KS,KY,LA,ME,MI,MS,MO,NE,NV,NH,NJ,NM,NY,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2016');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Accounting','AZ,FL,GA,ID,IL,IN,KS,KY,LA,MI,MS,MO,NH,NJ,NY,OH,OK,SC,TN,TX,VA,WA,WV,AL,AK,CA,CO,CT,DE,HI,ME,NE,NV,NM,ND,OR,PA,RI,SD,UT,VT,WI,WY','1975','2016');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Accounting','AR,MD,MT,NC','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Business Administration','MS','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Healthcare Management','AL,AK,AZ,AR,CA,CO,DE,DC,FL,GA,HI,ID,IL,IN,IA,KY,LA,ME,MD,MI,MN,MS,MO,MT,NE,NV,NH,NJ,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Human Services','AL,AK,AZ,AR,CA,CO,DE,DC,FL,GA,HI,ID,IL,IN,IA,KY,ME,MD,MI,MS,MO,MT,NE,NV,NH,NJ,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,WA,WV,WI,WY','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('4','Information Systems and Security','AZ,AR,FL,GA,ID,IL,IN,KS,KY,LA,MD,MI,MS,MO,MT,NH,NJ,NY,NC,OH,OK,SC,TN,TX,VA,WA,WV','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('6','Healthcare Management','AL,AK,AZ,CA,CO,CT,DE,DC,FL,GA,HI,ID,IL,IN,IA,KS,KY,LA,ME,MD,MA,MI,MN,MS,MO,MT,NE,NV,NH,NJ,NM,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2017');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('6','Business Administration','AL,AK,AZ,AR,CA,CO,CT,DE,DC,FL,GA,HI,ID,IL,IN,IA,KS,KY,LA,ME,MD,MA,MI,MN,MO,MT,NE,NV,NH,NJ,NM,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('6','Management and Leadership','AZ,AR,FL,GA,ID,IL,IN,KS,KY,LA,MD,MI,MS,MO,MT,NH,NJ,NY,NC,OH,OK,SC,TN,TX,VA,WA,WV','1975','2015');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('6','Business Administration','AL,AK,AZ,CA,CO,CT,DE,DC,FL,GA,HI,ID,IL,IN,IA,KS,KY,LA,ME,MD,MA,MI,MN,MS,MO,MT,NE,NV,NH,NJ,NM,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY','1975','2017');");
        DB::statement("INSERT INTO `scholarship_owl`.`double_positive_program` (`degree_type_id`,`program`,`states`,`min_hs_grad_year`,`max_hs_grad_year`) VALUES ('6','Management and Leadership','AL,AK,CA,CO,CT,DE,DC,HI,IA,ME,MA,MN,NE,NV,NM,ND,OR,PA,RI,SD,UT,VT,WI,WY','1975','2015');");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("double_positive_program");
    }
}
