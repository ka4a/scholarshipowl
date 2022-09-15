<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SupercollageIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('super_college_scholarship_match', function (Blueprint $table) {
            $table->foreign('super_college_scholarship_id', 'scs_id_foreign')
                ->references('id')
                ->on('super_college_scholarship')
                ->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('super_college_scholarship_match', function (Blueprint $table) {
            $table->dropForeign('super_college_scholarship_id');
        });
    }
}
