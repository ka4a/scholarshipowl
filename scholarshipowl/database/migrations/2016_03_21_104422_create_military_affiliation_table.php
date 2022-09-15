<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMilitaryAffiliationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			CREATE TABLE `military_affiliation` (
			  `military_affiliation_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			  `name` varchar(127) NOT NULL COMMENT 'Military affiliation name. Must be unique.',
			  PRIMARY KEY (`military_affiliation_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Table holds military affiliations.';
		");

		foreach(\ScholarshipOwl\Vendor\SuperCollege\Types::GetMilitaries() as $key => $value){
			$sql = "
				INSERT INTO `military_affiliation`
				(`military_affiliation_id`,
				`name`)
				VALUES
				(?,
				?);
			";

			DB::insert($sql, array($key, $value));
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("
			DROP TABLE `military_affiliation`;
		");
	}

}
