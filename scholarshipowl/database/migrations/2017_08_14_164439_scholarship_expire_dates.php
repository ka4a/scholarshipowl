<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Entity\Scholarship;

class ScholarshipExpireDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE `scholarship_owl`.`scholarship` 
            CHANGE COLUMN `expiration_date` `expiration_date` DATETIME NOT NULL,
            CHANGE COLUMN `start_date` `start_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ');

        \Schema::table('scholarship', function(Blueprint $table) {
            $table->string('timezone')->default(Scholarship::DEFAULT_TIMEZONE);
        });

        \DB::statement(
            "UPDATE scholarship
             SET start_date = CONCAT(DATE(start_date), ' 00:00:00'),
             expiration_date = CONCAT(DATE(expiration_date), ' 00:00:00')
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('scholarship', function(Blueprint $table) {
            $table->dropColumn('timezone');
        });

        \DB::statement('ALTER TABLE `scholarship_owl`.`scholarship` 
            CHANGE COLUMN `expiration_date` `expiration_date` TIMESTAMP NOT NULL,
            CHANGE COLUMN `start_date` `start_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ');
    }
}
