<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FeatureContentSetApplyNowButton extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->string('select_apply_now')->default('apply now')->after('register_hide_footer');
            $table->tinyInteger('select_hide_checkboxes')->default(0)->after('select_apply_now');
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
            $table->dropColumn('select_apply_now');
            $table->dropColumn('select_hide_checkboxes');
        });
    }
}
