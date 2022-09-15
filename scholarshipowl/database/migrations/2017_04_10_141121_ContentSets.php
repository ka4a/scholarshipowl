<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContentSets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('feature_block', function(Blueprint $table) {
            $table->text('register_header')->after('text');
            $table->renameColumn('text', 'homepage_header');

            $table->rename('feature_content_set');
        });

        \Schema::table('feature_set', function(Blueprint $table) {
            $table->renameColumn('homepage_top_block', 'content_set');
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
            $table->renameColumn('homepage_header', 'text');
            $table->rename('feature_block');
        });
        \DB::statement('ALTER TABLE `feature_block` DROP COLUMN `register_header`;');
        \Schema::table('feature_set', function(Blueprint $table) {
            $table->renameColumn('content_set', 'homepage_top_block');
        });
    }
}
