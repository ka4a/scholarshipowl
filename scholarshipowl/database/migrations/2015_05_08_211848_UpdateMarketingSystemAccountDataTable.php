<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMarketingSystemAccountDataTable extends Migration {
	public function up() {
		Schema::table('marketing_system_account_data', function(Blueprint $table) {
			$table->dropIndex('ix_application_account_id');
			
			$table->index('account_id', 'ix_marketing_system_account_data_account_id');
			$table->index('name', 'ix_marketing_system_account_data_name');
		});
	}

	public function down() {
		Schema::table('marketing_system_account_data', function(Blueprint $table) {
			$table->dropIndex('ix_marketing_system_account_data_account_id');
			$table->dropIndex('ix_marketing_system_account_data_name');
		});
	}
}
