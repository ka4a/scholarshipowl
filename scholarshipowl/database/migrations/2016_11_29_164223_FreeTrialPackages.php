<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Entity\TransactionalEmail;
use ScholarshipOwl\Util\Mailer;

class FreeTrialPackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('package', function(Blueprint $table) {
            $table->boolean('free_trial')->default(false)->after('expiration_date');
            $table->string('free_trial_period_type')->nullable()->after('free_trial');
            $table->string('free_trial_period_value')->nullable()->after('free_trial_period_type');
        });

        \Schema::table('subscription', function(Blueprint $table) {
            $table->boolean('free_trial')->default(false)->after('package_id');
            $table->timestamp('free_trial_end_date')->nullable()->after('free_trial');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('package', function(Blueprint $table) {
            $table->dropColumn('free_trial_period_value');
            $table->dropColumn('free_trial_period_type');
            $table->dropColumn('free_trial');
        });
        \Schema::table('subscription', function(Blueprint $table) {
            $table->dropColumn('free_trial_end_date');
            $table->dropColumn('free_trial');
        });
    }
}
