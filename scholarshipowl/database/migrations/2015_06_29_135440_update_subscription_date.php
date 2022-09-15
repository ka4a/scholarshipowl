<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class UpdateSubscriptionDate extends Migration {
	public function up() {
		\DB::statement("
			UPDATE subscription 
			SET 
				renewal_date = 
					CASE expiration_period_type
						WHEN 'day' THEN DATE_ADD(start_date, INTERVAL 1 DAY)
						WHEN 'week' THEN DATE_ADD(start_date, INTERVAL 1 WEEK)
						WHEN 'month' THEN DATE_ADD(start_date, INTERVAL 1 MONTH)
						WHEN 'year' THEN DATE_ADD(start_date, INTERVAL 1 YEAR)
					END,
				end_date = 
					CASE 
						WHEN expiration_period_value = 9999 THEN 
							DATE_ADD(start_date, INTERVAL 20 YEAR) 
						ELSE 
							CASE expiration_period_type
								WHEN 'day' THEN DATE_ADD(start_date, INTERVAL expiration_period_value DAY)
								WHEN 'week' THEN DATE_ADD(start_date, INTERVAL expiration_period_value WEEK)
								WHEN 'month' THEN DATE_ADD(start_date, INTERVAL expiration_period_value MONTH)
								WHEN 'year' THEN DATE_ADD(start_date, INTERVAL expiration_period_value YEAR)
							END
					END 
			WHERE expiration_type = 'recurrent';
		");
		
		\DB::statement("
			UPDATE subscription
			SET end_date = DATE_ADD(start_date, INTERVAL 20 YEAR) 
			WHERE expiration_type = 'no_expiry';
		");
		
		\DB::statement("
			UPDATE subscription
			SET end_date = DATE_ADD(NOW(), INTERVAL -1 DAY)
			WHERE expiration_type = 'date'
			AND end_date = '0000-00-00 00:00:00';
		");
	}

	public function down() {
		
	}
}
