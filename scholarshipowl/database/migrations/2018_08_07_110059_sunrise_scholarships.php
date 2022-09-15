<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SunriseScholarships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('scholarship', function (Blueprint $table) {
            $table->string('external_scholarship_id', 40)->nullable();
            $table->unique('external_scholarship_id');
            $table->string('external_scholarship_template_id', 40)->nullable();
            $table->index('external_scholarship_template_id');
        });

        \Schema::table('application', function (Blueprint $table) {
            $table->string('external_scholarship_template_id', 40)->nullable();
            $table->foreign('external_scholarship_template_id')
                ->references('external_scholarship_template_id')
                ->on('scholarship');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('scholarship', function (Blueprint $table) {
            $table->dropColumn('external_scholarship_id');
            $table->dropColumn('external_scholarship_template_id');
        });

        \Schema::table('application', function (Blueprint $table) {
            $table->dropColumn('external_scholarship_template_id');
        });
    }
}
