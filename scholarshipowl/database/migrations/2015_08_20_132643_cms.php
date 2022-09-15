<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cms extends Migration {

    public function up() {
        \DB::statement("
			CREATE TABLE `cms` (
			`cms_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			`url` varchar(127) NOT NULL COMMENT 'Relative Url.',
			`page` varchar(127) NOT NULL COMMENT 'Page Name',
			`title` varchar(127) NOT NULL COMMENT 'Meta Title for this page.',
			`keywords` varchar(2045) NOT NULL COMMENT 'Meta Keywords for this page.',
			`description` varchar(2045) NOT NULL COMMENT 'Meta Description for this page.',
			`author` varchar(2045) NOT NULL COMMENT 'Meta Author for this page.',
			 KEY `ix_cms_url` (`url`),
			PRIMARY KEY(`cms_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds cms page list mostly for seo meta data.'
		;");
    }

    public function down() {
        \DB::statement("DROP TABLE `cms`");
    }

}
