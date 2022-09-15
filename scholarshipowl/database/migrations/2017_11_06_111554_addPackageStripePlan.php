<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackageStripePlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('package', function(Blueprint $table) {
            $table->string('stripe_plan')->after('recurly_plan')->nullable();
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
            $table->dropColumn('stripe_plan');
        });
    }
}
