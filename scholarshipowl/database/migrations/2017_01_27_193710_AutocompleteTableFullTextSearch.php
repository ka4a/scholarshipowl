<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AutocompleteTableFullTextSearch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE FULLTEXT INDEX autocomplete_text ON college(canonical_name);');
        \DB::statement('CREATE FULLTEXT INDEX autocomplete_text ON highschool(name);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('ALTER TABLE college DROP INDEX autocomplete_text;');
        \DB::statement('ALTER TABLE highschool DROP INDEX autocomplete_text;');
    }
}
