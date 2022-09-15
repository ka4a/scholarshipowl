<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RequirementTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('requirement_text', function(Blueprint $table) {
            $table->char('permanent_tag', 20)->after('title')->nullable(false);
        });
        \DB::statement("
            UPDATE requirement_text
            SET permanent_tag = LEFT(UUID(), 8);
        ");
        \Schema::table('requirement_text', function(Blueprint $table) {
            $table->unique(['permanent_tag', 'scholarship_id']);
        });

        \Schema::table('requirement_input', function(Blueprint $table) {
            $table->char('permanent_tag', 20)->after('title')->nullable(true);
        });
        \DB::statement("
            UPDATE requirement_input
            SET permanent_tag = LEFT(UUID(), 8);
        ");
        \Schema::table('requirement_input', function(Blueprint $table) {
            $table->unique(['permanent_tag', 'scholarship_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('requirement_text', function(Blueprint $table) {
            $table->dropUnique(['permanent_tag', 'scholarship_id']);
        });

        \Schema::table('requirement_input', function(Blueprint $table) {
            $table->dropUnique(['permanent_tag', 'scholarship_id']);
        });

        \Schema::table('requirement_text', function(Blueprint $table) {
            $table->dropColumn('permanent_tag');
        });

        \Schema::table('requirement_input', function(Blueprint $table) {
            $table->dropColumn('permanent_tag');
        });
    }
}
