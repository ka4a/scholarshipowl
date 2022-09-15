<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Register3PageContentSetConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->text('register3_heading_text')->nullable()->after('register3_header');
            $table->text('register3_subheading_text')->nullable()->after('register3_header');
            $table->string('register3_cta_text')->default("continue")->after('register3_header');
        });

        DB::update("
            UPDATE feature_content_set SET
            register3_heading_text = 'Just a few more things',
            register3_subheading_text = 'And your matches will be ready for you!'
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
            $table->dropColumn('register3_subheading_text');
            $table->dropColumn('register3_heading_text');
            $table->dropColumn('register3_cta_text');
        });
    }
}
