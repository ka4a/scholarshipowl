<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Reg3Fset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->text('register3_header')->nullable(false);
            $table->text('register_cta_text')->nullable(false);
        });

        DB::update("
            UPDATE feature_content_set SET
            register3_header = ?",
            ['Just a few more things, and&nbsp;you\'ll be ready to apply']
        );

        DB::update("
            UPDATE feature_content_set SET
            register_cta_text = ?",
            ['register for free']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->dropColumn('register3_header');
            $table->dropColumn('register_cta_text');
        });
    }
}
