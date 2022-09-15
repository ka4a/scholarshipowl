<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZuUsaTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP TABLE IF EXISTS `zu_usa_program`;");
        DB::statement("DROP TABLE IF EXISTS `zu_usa_campus`;");
        DB::statement("DROP TABLE IF EXISTS `zu_usa_campaign_allocation`;");
        DB::statement("DROP TABLE IF EXISTS `zu_usa_campaign`;");

        DB::statement("CREATE TABLE `zu_usa_campaign` (
          `zu_usa_campaign_id` int(11) unsigned NOT NULL COMMENT 'Primary key, id defined in docs.',
          `name` varchar(100) NOT NULL COMMENT 'Campaign name.',
          `daily_cap` int(5) unsigned DEFAULT NULL COMMENT 'Daily campaign allocation.',
          `monthly_cap` int(5) unsigned DEFAULT NULL COMMENT 'Monthly campaign allocation.',
          `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Is the campaign active',
          `submission_url` varchar(255) DEFAULT NULL,
          PRIMARY KEY (`zu_usa_campaign_id`),
          UNIQUE KEY `zu_usa_campaign_id_UNIQUE` (`zu_usa_campaign_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table holds Zu USA coreg campaigns and capping rules.';");


        DB::statement("CREATE TABLE `zu_usa_campaign_allocation` (
          `zu_usa_campaign_id` int(11) unsigned NOT NULL COMMENT 'Foreign key from zu_usa_campaign table',
          `type` enum('day','month') NOT NULL DEFAULT 'month' COMMENT 'Type of the counter, daily or monthly',
          `date` date NOT NULL COMMENT 'Date for counter',
          `count` int(5) unsigned NOT NULL DEFAULT '1' COMMENT 'Submissions count',
          PRIMARY KEY (`zu_usa_campaign_id`,`date`,`type`),
          CONSTRAINT `fk_zu_usa_campaign_daily_allocation_zu_usa_campaign` FOREIGN KEY (`zu_usa_campaign_id`) REFERENCES `zu_usa_campaign` (`zu_usa_campaign_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table holds allocated submissions for Zu USA coreg';");


        DB::statement("CREATE TABLE `zu_usa_campus` (
          `zu_usa_campus_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
          `zu_usa_campaign_id` int(11) unsigned NOT NULL COMMENT 'Zu USA campaign id. Foreign key from zu_usa_campaign table.',
          `submission_value` varchar(45) NOT NULL COMMENT 'Value to be submitted',
          `display_value` varchar(100) NOT NULL COMMENT 'Value to be displayed',
          `zip` mediumtext COMMENT 'Zip code dependency list',
          PRIMARY KEY (`zu_usa_campus_id`),
          UNIQUE KEY `zu_usa_campus_id_UNIQUE` (`zu_usa_campus_id`),
          KEY `fk_zu_usa_campus_zu_usa_campaign_idx` (`zu_usa_campaign_id`),
          CONSTRAINT `fk_zu_usa_campus_zu_usa_campaign` FOREIGN KEY (`zu_usa_campaign_id`) REFERENCES `zu_usa_campaign` (`zu_usa_campaign_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
        ) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=latin1 COMMENT='Table holds campus values and dependencies for Zu USA coreg.';");


        DB::statement("CREATE TABLE `zu_usa_program` (
          `zu_usa_program_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
          `zu_usa_campaign_id` int(11) unsigned NOT NULL COMMENT 'Zu USA campaign id. Foreign key from zu_usa_campaign table.',
          `submission_value` varchar(100) NOT NULL COMMENT 'Value to be submitted',
          `display_value` varchar(100) NOT NULL COMMENT 'Value to be displayed',
          `campus` varchar(45) DEFAULT NULL COMMENT 'Campus dependency list',
          `zip` mediumtext COMMENT 'Zip code dependency list',
          `last_degree_completed` mediumtext COMMENT 'Last degree completed dependency list',
          `state` mediumtext COMMENT 'State dependency list',
          PRIMARY KEY (`zu_usa_program_id`),
          UNIQUE KEY `zu_usa_program_id_UNIQUE` (`zu_usa_program_id`),
          KEY `fk_zu_usa_program_zu_usa_campaign_idx` (`zu_usa_campaign_id`),
          CONSTRAINT `fk_zu_usa_program_zu_usa_campaign` FOREIGN KEY (`zu_usa_campaign_id`) REFERENCES `zu_usa_campaign` (`zu_usa_campaign_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
        ) ENGINE=InnoDB AUTO_INCREMENT=718 DEFAULT CHARSET=latin1 COMMENT='Table holds values and dependencies for Zu USA coreg programs';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP TABLE IF EXISTS `zu_usa_program`;");
        DB::statement("DROP TABLE IF EXISTS `zu_usa_campus`;");
        DB::statement("DROP TABLE IF EXISTS `zu_usa_campaign_allocation`;");
        DB::statement("DROP TABLE IF EXISTS `zu_usa_campaign`;");
    }
}
