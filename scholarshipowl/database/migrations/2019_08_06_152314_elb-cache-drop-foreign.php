<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ElbCacheDropForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eligibility_cache', function (Blueprint $table) {
            $table->dropForeign('fk_eligibility_cache_account_id');
        });

        // clean up old eligibility implementation
        Schema::dropIfExists('account_eligibility');
        Schema::table('account', function (Blueprint $table) {
            $table->dropColumn('eligibility_id');
            $table->dropColumn('eligibility_update');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account', function (Blueprint $table) {
            $table->foreign('account_id','fk_eligibility_cache_account_id')->references('account_id')->on('account');
        });
    }
}
