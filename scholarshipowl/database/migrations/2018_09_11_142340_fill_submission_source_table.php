<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillSubmissionSourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("submission_sources")->insert(array(
            "id" => 1,
            "source" => "desktop",
        ));

        DB::table("submission_sources")->insert(array(
            "id" => 2,
            "source" => "mobile",
        ));

        DB::table("submission_sources")->insert(array(
            "id" => 3,
            "source" => "system",
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete("DELETE FROM submission_sources WHERE id = 1;");
        DB::delete("DELETE FROM submission_sources WHERE id = 2;");
        DB::delete("DELETE FROM submission_sources WHERE id = 3;");
    }
}
