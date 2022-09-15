<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ScholarshipFileTablesCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('scholarship_file_account_file_type');
        Schema::dropIfExists('scholarship_file');

        Schema::create('scholarship_file', function (Blueprint $table) {
            $table->increments('scholarship_file_id');
            $table->unsignedInteger('scholarship_id');
            $table->unsignedInteger('category_id');
            $table->text('description');
            $table->unsignedInteger('max_size');
            $table->timestamps();

            $table->foreign('scholarship_id', 'fk_scholarship_file_scholarship')
                ->on('scholarship')
                ->references('scholarship_id');

            $table->foreign('category_id', 'fk_scholarship_file_account_file_category')
                ->on('account_file_categories')
                ->references('id');
        });

        Schema::create('scholarship_file_account_file_type', function(Blueprint $table) {
            $table->unsignedInteger('scholarship_file_id');
            $table->unsignedInteger('file_type_id');

            $table->primary(['scholarship_file_id', 'file_type_id'], 'pk_scholarship_file_account_file_type');

            $table->foreign('scholarship_file_id', 'fk_scholarship_file_account_file_type_scholarship')
                ->on('scholarship_file')
                ->references('scholarship_file_id');

            $table->foreign('file_type_id', 'fk_scholarship_file_account_file_type_account_file_type')
                ->on('account_file_type')
                ->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scholarship_file_account_file_type');
        Schema::dropIfExists('scholarship_file');
    }
}
