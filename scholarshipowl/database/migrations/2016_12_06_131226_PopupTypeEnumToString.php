<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PopupTypeEnumToString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement(
            "ALTER TABLE `scholarship_owl`.`popup` CHANGE COLUMN `popup_type` `popup_type` VARCHAR(255) NULL DEFAULT 'popup';"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
