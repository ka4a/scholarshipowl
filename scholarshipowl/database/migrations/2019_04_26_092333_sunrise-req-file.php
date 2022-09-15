<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SunriseReqFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table("requirement_name")->insert([
            'name' => 'Transcript',
            'type' => 2,
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('requirement_name')
            ->where(['name' => 'Transcript', 'type' => 2])
            ->delete();
    }

}
