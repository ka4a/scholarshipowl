<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoregPluginAllocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
			CREATE TABLE `coreg_plugin_allocation` (
			  `coreg_plugin_id` int(11) unsigned NOT NULL COMMENT 'Foreign key from coreg_plugin table',
			  `type` enum('day','month') NOT NULL DEFAULT 'month' COMMENT 'Type of the counter, daily or monthly',
			  `date` date NOT NULL COMMENT 'Date for counter',
			  `count` int(5) unsigned NOT NULL DEFAULT '1' COMMENT 'Submissions count',
			  PRIMARY KEY (`coreg_plugin_id`,`date`,`type`),
			  CONSTRAINT `fk_coreg_plugin_allocation_coreg_plugin` FOREIGN KEY (`coreg_plugin_id`) REFERENCES `coreg_plugins` (`coreg_plugin_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table holds allocated submissions for coreg plugins';
		");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("
			DROP TABLE `coreg_plugin_allocation`;
		");
    }
}
