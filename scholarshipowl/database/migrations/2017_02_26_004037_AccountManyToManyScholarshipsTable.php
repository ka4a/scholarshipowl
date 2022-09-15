<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountManyToManyScholarshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        \DB::statement('ALTER TABLE `account` DROP FOREIGN KEY `fk_account_eligibility_id`');
//        \DB::statement('ALTER TABLE `account` DROP COLUMN `eligibility_id`, DROP INDEX `fk_account_eligibility_id_idx`;');
//        \DB::statement('DROP TABLE `account_eligibility`;');

//        \DB::statement(
//            'CREATE TABLE `account_eligibility` (
//              `id` VARCHAR(32) NOT NULL,
//              `scholarship_id` INT UNSIGNED NOT NULL,
//              PRIMARY KEY (`id`, `scholarship_id`))
//            CHARACTER SET utf8;'
//        );
//
//        \DB::statement('ALTER TABLE `account` ADD COLUMN `eligibility_id` VARCHAR(32) AFTER `eligibility_update`;');

//        \DB::statement(
//            'ALTER TABLE `account`
//            ADD CONSTRAINT `fk_account_eligibility_id`
//              FOREIGN KEY (`eligibility_id`)
//              REFERENCES `account_eligibility` (`id`)
//              ON DELETE NO ACTION
//              ON UPDATE NO ACTION;'
//        );

        /*
        \Schema::dropIfExists('account_eligibility');
        \Schema::create('account_eligibility', function(Blueprint $table) {
            $table->binary('id', 32);
            $table->index('id');

            $table->unsignedInteger('scholarship_id');
            $table->foreign('scholarship_id')->references('scholarship_id')->on('scholarship');

            $table->primary(['id', 'scholarship_id']);
        });
        */

//        \Schema::table('account', function(Blueprint $table) {
//            $table->dropColumn('eligibility_id');
//        });

        /*
        \Schema::table('account', function(Blueprint $table) {
            $table->binary('eligibility_id', 32);
            $table->foreign('eligibility_id', 'fk_account_eligibility_id')->references('id')->on('account_eligibility');
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        \DB::statement('ALTER TABLE `account` DROP FOREIGN KEY `fk_account_eligibility_id`');
//        \DB::statement('ALTER TABLE `account` DROP COLUMN `eligibility_id`, DROP INDEX `fk_account_eligibility_id_idx`;');
//        \DB::statement('DROP TABLE `account_eligibility`;');

//        \Schema::dropIfExists('account_eligibility');
//        \Schema::table('account', function(Blueprint $table) {
//            $table->dropForeign('fx_account_eligibility_id');
//            $table->dropColumn('eligibility_id');
//        });
    }
}
