<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPackageTable2 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        return \DB::statement("ALTER TABLE `package`
        ADD COLUMN `message` VARCHAR(4095) NULL AFTER `priority`,
        ADD COLUMN `success_message` VARCHAR(4095) NULL AFTER `message`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        return \DB::statement("ALTER TABLE `package`
        DROP COLUMN `success_message`,
        DROP COLUMN `message`;");
    }

}
