<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PackageFreeTrialName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('package', function(Blueprint $table) {
            $table->string('free_trial_package_name')->after('free_trial');
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
            $table->dropColumn('free_trial_package_name');
        });
    }
}
