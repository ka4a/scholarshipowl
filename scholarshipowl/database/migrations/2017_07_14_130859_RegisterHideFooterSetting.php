<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RegisterHideFooterSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->boolean('register_hide_footer')
                ->default(false)
                ->after('register_header');
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
            $table->dropColumn('register_hide_footer');
        });
    }
}
