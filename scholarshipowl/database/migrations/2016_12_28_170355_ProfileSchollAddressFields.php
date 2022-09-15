<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProfileSchollAddressFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('profile', function(Blueprint $table) {
            $table->string('highschool_address1')->nullable()->after('highschool');
            $table->string('highschool_address2')->nullable()->after('highschool');
            $table->string('university_address1')->nullable()->after('university');
            $table->string('university_address2')->nullable()->after('university');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('profile', function(Blueprint $table) {
            $table->removeColumn('highschool_address1');
            $table->removeColumn('highschool_address2');
            $table->removeColumn('university_address1');
            $table->removeColumn('university_address2');
        });
    }
}
