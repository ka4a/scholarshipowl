<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LoginHistoryImprovmentFset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('login_history', function (Blueprint $table) {
            $table->unsignedInteger('fset')->after('action')->nullable();
            $table->text('agent')->nullable();
            $table->string('srv')->after('fset')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('ALTER TABLE `login_history` DROP COLUMN `fset`');
        \DB::statement('ALTER TABLE `login_history` DROP COLUMN `agent`');
        \DB::statement('ALTER TABLE `login_history` DROP COLUMN `srv`');
    }
}
