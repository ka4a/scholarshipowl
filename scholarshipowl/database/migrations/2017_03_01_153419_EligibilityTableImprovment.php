<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Entity\Eligibility;
use App\Entity\Field;

class EligibilityTableImprovment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        \DB::statement(sprintf('UPDATE eligibility SET type = %s WHERE type = "%s"', Eligibility::TYPE_REQUIRED, 'required'));
//        \DB::statement(sprintf('UPDATE eligibility SET type = %s WHERE type = "%s"', Eligibility::TYPE_VALUE, 'value'));
//        \DB::statement(sprintf('UPDATE eligibility SET type = %s WHERE type = "%s"', Eligibility::TYPE_LESS_THAN, 'less_than'));
//        \DB::statement(sprintf('UPDATE eligibility SET type = %s WHERE type = "%s"', Eligibility::TYPE_GREATER_THAN, 'greater_than'));
//        \DB::statement(sprintf('UPDATE eligibility SET type = %s WHERE type = "%s"', Eligibility::TYPE_BETWEEN, 'between'));
//        \DB::statement(sprintf('UPDATE eligibility SET type = %s WHERE type = "%s"', Eligibility::TYPE_NOT, 'not'));
//        \DB::statement(sprintf('UPDATE eligibility SET type = %s WHERE type = "%s"', Eligibility::TYPE_IN, 'in'));

//        \DB::statement(sprintf('UPDATE eligibility SET field_id = %s WHERE field_id = %s', Field::STATE, Field::STATE_ABBREVIATION));
//        \DB::statement(sprintf('DELETE FROM  eligibility WHERE field_id = %s', Field::EMAIL_CONFIRMATION));

//        \DB::statement('ALTER TABLE eligibility MODIFY type TINYINT');
//        \DB::statement('ALTER TABLE `eligibility` ADD INDEX `ix_eligibility_type` (`type` ASC);');
//        \DB::statement('UPDATE eligibility SET value = TRIM(BOTH \'"\' FROM value)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        \DB::statement('ALTER TABLE eligibility MODIFY type VARCHAR(32)');

//        \DB::statement(sprintf('UPDATE eligibility SET type = "%s" WHERE type = %s', 'required', Eligibility::TYPE_REQUIRED));
//        \DB::statement(sprintf('UPDATE eligibility SET type = "%s" WHERE type = %s', 'value', Eligibility::TYPE_VALUE));
//        \DB::statement(sprintf('UPDATE eligibility SET type = "%s" WHERE type = %s', 'less_than', Eligibility::TYPE_LESS_THAN));
//        \DB::statement(sprintf('UPDATE eligibility SET type = "%s" WHERE type = %s', 'greater_than', Eligibility::TYPE_GREATER_THAN));
//        \DB::statement(sprintf('UPDATE eligibility SET type = "%s" WHERE type = %s', 'between', Eligibility::TYPE_BETWEEN));
//        \DB::statement(sprintf('UPDATE eligibility SET type = "%s" WHERE type = %s', 'not', Eligibility::TYPE_NOT));
//        \DB::statement(sprintf('UPDATE eligibility SET type = "%s" WHERE type = %s', 'in', Eligibility::TYPE_IN));

//        \DB::statement('UPDATE eligibility SET value = CONCAT(\'"\', value, \'"\')');
    }
}
