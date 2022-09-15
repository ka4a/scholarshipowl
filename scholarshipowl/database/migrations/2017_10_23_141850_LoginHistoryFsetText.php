<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LoginHistoryFsetText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `login_history` CHANGE COLUMN `action` `action` VARCHAR(16) NOT NULL DEFAULT 'login';");

        Schema::table('login_history', function (Blueprint $table) {
            $table->string('fset')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('login_history', function (Blueprint $table) {
            $table->unsignedInteger('fset')->change();
        });
    }
}
