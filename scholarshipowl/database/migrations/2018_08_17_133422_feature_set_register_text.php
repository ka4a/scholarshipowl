<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FeatureSetRegisterText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->text('register_heading_text')->nullable();
            $table->text('register_subheading_text')->nullable();
        });

        DB::update("
            UPDATE feature_content_set SET
            register_heading_text = 'Congratulations!',
            register_subheading_text = 'You are eligible to automatically apply for up to'
        ");

        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->text('register_heading_text')->nullable(false)->change();
            $table->text('register_subheading_text')->nullable(false)->change();
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
            $table->dropColumn('register_heading_text');
            $table->dropColumn('register_subheading_text');
        });
    }
}
