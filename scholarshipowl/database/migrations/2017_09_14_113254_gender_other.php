<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Entity\Field;
use App\Entity\Form;
use App\Entity\Eligibility;
use App\Entity\Profile;

class GenderOther extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE `profile` CHANGE COLUMN `gender` `gender` VARCHAR(255) NULL DEFAULT NULL;');
        \DB::statement('UPDATE `eligibility` SET `type` = "in", `value` = "\"female,male\"" WHERE `type` = "required" AND `field_id` = 10');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('UPDATE `eligibility` SET `type` = "required", `value` = "\"0\"" WHERE `type` = "in" AND `field_id` = 10');
    }
}
