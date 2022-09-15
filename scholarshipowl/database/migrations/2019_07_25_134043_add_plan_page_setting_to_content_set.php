<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlanPageSettingToContentSet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->text('pp_header_text')->nullable()->after('register3_header');
            $table->string('pp_button_text')->default("Activate 7 day free trial")->after('register3_header');
            $table->string('pp_below_button_text')->default("Yes! It\'s totally free")->after('register3_header');
            $table->string('pp_carousel_items_cnt')->after('register3_header');
        });

        DB::update("
            UPDATE feature_content_set SET
            pp_header_text = 'You quality for [[eligible_scholarships_count]] scholarships worth [[eligible_scholarships_amount]]'
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->dropColumn('pp_header_text');
            $table->dropColumn('pp_button_text');
            $table->dropColumn('pp_below_button_text');
            $table->dropColumn('pp_carousel_items_cnt');
        });
    }
}
