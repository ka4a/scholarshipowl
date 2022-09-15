<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EssayFilesTableFileRelationUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('essay_files', function (Blueprint $table) {
            $table->dropForeign('fk_file');
            $table->unsignedInteger('file_id')->nullable()->change();
            $table->unsignedInteger('account_file_id')->nullable();
            $table->foreign('account_file_id', 'fk_account_file')
                ->on('account_file')
                ->references('id')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('essay_files', function (Blueprint $table) {
            $table->dropForeign('fk_account_file');
            $table->dropColumn('account_file_id');
        });
    }
}
