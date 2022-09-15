<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMilitaryAffiliationList extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			ALTER TABLE `profile`
			DROP FOREIGN KEY `fk_profile_military_affiliation`;
		");

		DB::statement("
			TRUNCATE `military_affiliation`;
		");

		DB::statement("
			ALTER TABLE `military_affiliation` AUTO_INCREMENT = 1;
		");

		DB::statement("
			INSERT INTO `military_affiliation`
			(`military_affiliation_id`,	`name`)
			VALUES
			(1, 'Army'),
			(2, 'Navy'),
			(3, 'Air Force'),
			(4, 'Marines'),
			(5, 'National Guard'),
			(6, 'Coast Guard'),
			(7, 'Civil Air Patrol'),
			(8, 'Marine Corps League'),
			(9, 'Active Military, child of'),
			(10, 'Active Military, spouse of'),
			(11, 'Reserve'),
			(12, 'Reserve, child of'),
			(13, 'Reserve, spouse of'),
			(14, 'Veteran'),
			(15, 'Veteran, child of'),
			(16, 'Veteran, spouse of'),
			(17, 'Medal of Honor recipient'),
			(18, 'Medal of Honor, child of recipient'),
			(19, 'Medal of Honor, spouse of recipient'),
			(20, 'Retired'),
			(21, 'Retired, child of'),
			(22, 'Retired, spouse of'),
			(23, 'Dismissed'),
			(24, 'Dismissed, child of'),
			(25, 'Dismissed, spouse of'),
			(26, 'Disabled'),
			(27, 'Disabled, child of'),
			(28, 'Disabled, spouse of'),
			(999, 'None');
		");

		DB::statement("
			UPDATE `profile`
			SET military_affiliation_id = 1
			WHERE military_affiliation_id = 39
		");

		DB::statement("
			UPDATE `profile`
			SET military_affiliation_id = 4
			WHERE military_affiliation_id = 23
		");

		DB::statement("
			UPDATE `profile`
			SET military_affiliation_id = 9
			WHERE military_affiliation_id IN (8, 10, 12, 14, 16, 18, 22, 33, 34, 35, 40, 43)
		");

		DB::statement("
			UPDATE `profile`
			SET military_affiliation_id = 10
			WHERE military_affiliation_id IN (9, 11, 13, 15, 17, 19, 21, 49)
		");

		DB::statement("
			UPDATE `profile`
			SET military_affiliation_id = 14
			WHERE military_affiliation_id IN (50, 51)
		");

		DB::statement("
			UPDATE `profile`
			SET military_affiliation_id = 15
			WHERE military_affiliation_id IN (24, 31, 32, 36, 37, 41, 42, 47)
		");

		DB::statement("
			UPDATE `profile`
			SET military_affiliation_id = 17
			WHERE military_affiliation_id = 28
		");

		DB::statement("
			UPDATE `profile`
			SET military_affiliation_id = 18
			WHERE military_affiliation_id = 29
		");

		DB::statement("
			UPDATE `profile`
			SET military_affiliation_id = 19
			WHERE military_affiliation_id = 30
		");

		DB::statement("
			UPDATE `profile`
			SET military_affiliation_id = 11
			WHERE military_affiliation_id IN (38, 44, 45, 46)
		");

		DB::statement("
			UPDATE `profile`
			SET military_affiliation_id = 8
			WHERE military_affiliation_id = 48
		");

		DB::statement("
			UPDATE `profile`
			SET military_affiliation_id = 28
			WHERE military_affiliation_id IN (25, 26, 27)
		");

		DB::statement("
			UPDATE `military_affiliation` SET military_affiliation_id = 0 where military_affiliation_id = 999;
		");

		DB::statement("
			ALTER TABLE `profile`
			ADD CONSTRAINT `fk_profile_military_affiliation`
			  FOREIGN KEY (`military_affiliation_id`)
			  REFERENCES `military_affiliation` (`military_affiliation_id`)
			  ON DELETE NO ACTION
			  ON UPDATE NO ACTION;
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
			ALTER TABLE `profile`
			DROP FOREIGN KEY `fk_profile_military_affiliation`;
		");

		DB::statement("
			TRUNCATE `military_affiliation`;
		");

		DB::statement("
			ALTER TABLE `military_affiliation` AUTO_INCREMENT = 1;
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

		DB::statement("
			INSERT INTO `military_affiliation`
			(`military_affiliation_id`,
			`name`)
			VALUES
			(999,
			'None');
		");

		DB::statement("
			UPDATE `military_affiliation` SET military_affiliation_id = 0 where military_affiliation_id = 999;
		");

		DB::statement("
			ALTER TABLE `profile`
			ADD CONSTRAINT `fk_profile_military_affiliation`
			  FOREIGN KEY (`military_affiliation_id`)
			  REFERENCES `military_affiliation` (`military_affiliation_id`)
			  ON DELETE NO ACTION
			  ON UPDATE NO ACTION;
		");
	}

}
