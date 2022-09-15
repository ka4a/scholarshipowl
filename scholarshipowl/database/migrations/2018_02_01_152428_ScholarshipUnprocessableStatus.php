<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Entity\ScholarshipStatus;

class ScholarshipUnprocessableStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('scholarship_status')->insert(['id' => ScholarshipStatus::UNPROCESSABLE, 'name' => 'Unprocessable']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('scholarship_status')->where(['id' => ScholarshipStatus::UNPROCESSABLE])->delete();
    }
}
