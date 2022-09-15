<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountFileTypeCreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET foreign_key_checks = 0;');
        Schema::dropIfExists('account_file_type');
        Schema::create('account_file_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });
        DB::statement('SET foreign_key_checks = 1;');

        DB::table('account_file_type')->insert([
            ['id' => 1, 'name' => 'Unknown'],
            ['id' => 2, 'name' => 'Text'],
            ['id' => 3, 'name' => 'Image'],
            ['id' => 4, 'name' => 'Tables'],
            ['id' => 5, 'name' => 'Video'],
        ]);

        Schema::table('account_file', function(Blueprint $table) {
            $table->dropColumn('type_id');
        });
        Schema::table('account_file', function (Blueprint $table) {
            $table->unsignedInteger('type_id')->after('category_id');
            $table->foreign('type_id', 'fk_account_file_account_file_type')
                ->references('id')
                ->on('account_file_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_file', function(Blueprint $table) {
            $table->dropForeign('fk_account_file_account_file_type');
            $table->dropColumn('type_id');
        });

        Schema::dropIfExists('account_file_type');
    }
}
