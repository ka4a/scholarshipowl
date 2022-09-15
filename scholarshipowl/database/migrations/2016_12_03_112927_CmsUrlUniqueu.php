<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CmsUrlUniqueu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('cms', function(Blueprint $table) {
            $table->unique(['url'], 'uk_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('cms', function(Blueprint $table) {
            $table->dropUnique('uk_url');
        });
    }
}
