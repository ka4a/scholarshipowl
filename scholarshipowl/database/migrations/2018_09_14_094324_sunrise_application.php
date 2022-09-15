<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SunriseApplication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('application', function (Blueprint $table) {
            $table->string('external_application_id', 40)->nullable(true);
            $table->index('external_application_id');

            $table->smallInteger('external_status')->nullable(true);
            $table->index('external_status');

            $table->dateTime('external_status_updated_at')->nullable(true);
        });

        \Schema::table('scholarship', function (Blueprint $table) {
            $table->string('transitional_status', 40)->nullable(true);
            $table->string('winner_form_url')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('application', function (Blueprint $table) {
            $table->dropColumn('external_application_id');
            $table->dropColumn('external_status');
            $table->dropColumn('external_status_updated_at');
        });

        \Schema::table('scholarship', function (Blueprint $table) {
            $table->dropColumn('transitional_status');
            $table->dropColumn('winner_form_url');
        });
    }
}
