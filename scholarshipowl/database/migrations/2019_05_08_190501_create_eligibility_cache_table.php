<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEligibilityCacheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eligibility_cache', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->integer('account_id', false, true);
            $table->dateTime('updated_at');
            $table->json('last_shown_scholarship_ids')->nullable(true);
            $table->json('eligible_scholarship_ids')->nullable(true);
            $table->foreign('account_id','fk_eligibility_cache_account_id')->references('account_id')->on('account');

            $table->unique('account_id');
        });

//        Schema::dropIfExists('account_eligibility');
//        Schema::table('account', function (Blueprint $table) {
//            $table->dropColumn('eligibility_id');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eligibility_cache');
    }
}
