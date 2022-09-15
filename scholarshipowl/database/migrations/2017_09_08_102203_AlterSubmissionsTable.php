<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::query("ALTER TABLE `submission` 
CHANGE COLUMN `status` `status` VARCHAR(10) NOT NULL DEFAULT 'inactive' COMMENT 'Submissions status.';");
        Schema::table("submission", function (Blueprint $table) {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("submissions", function (Blueprint $table) {
            //$table->enum("status", ["pending", "success", "error"]);
            $table->dropTimestamps();
        });
    }
}
