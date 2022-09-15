<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Regiser2PageContentSetConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->text('register2_heading_text')->nullable();
            $table->text('register2_subheading_text')->nullable();
            $table->string('register2_cta_text')->default("continue");
        });

        DB::update("
        UPDATE feature_content_set SET
        register2_heading_text = 'The more you give',
        register2_subheading_text = 'The more you receive'
        ");

        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->text('register2_heading_text')->nullable(false)->change();
            $table->text('register2_subheading_text')->nullable(false)->change();
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
            $table->dropColumn('register2_heading_text');
            $table->dropColumn('register2_subheading_text');
            $table->dropColumn('register2_cta_text');
        });
    }
}
