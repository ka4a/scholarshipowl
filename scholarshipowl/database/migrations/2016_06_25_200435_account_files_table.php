<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('account_file');
        Schema::dropIfExists('account_file_categories');

        Schema::create('account_file_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        DB::table('account_file_categories')->insert([
            ['name' => 'Other'],
            ['name' => 'Essay'],
            ['name' => 'Grade transcript'],
            ['name' => 'Bio'],
            ['name' => 'CV'],
            ['name' => 'Profile picture'],
        ]);

        Schema::create('account_file', function (Blueprint $table) {
            $table->string('path');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('category_id');
            $table->string('file_name');
            $table->timestamps();

            $table->primary(['path']);
            $table->foreign('account_id')->references('account_id')->on('account');
            $table->foreign('category_id')->references('id')->on('account_file_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_file');
        Schema::dropIfExists('account_file_categories');
    }
}
