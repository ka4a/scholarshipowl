<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ScholarshipEligibilityUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('scholarship', function(Blueprint $table) {
            $table->timestamp('eligibility_update')->nullable();
        });

        \DB::update('UPDATE `scholarship` SET `eligibility_update` = NOW();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('scholarship', function(Blueprint $table) {
            $table->dropColumn('eligibility_update');
        });
    }
}
