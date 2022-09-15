<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContentSetRegisterFlowIllustrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->text('register_illustration')->nullable();
            $table->text('register2_illustration')->nullable();
            $table->text('register3_illustration')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->dropColumn('register_illustration');
            $table->dropColumn('register2_illustration');
            $table->dropColumn('register3_illustration');
        });
    }
}
