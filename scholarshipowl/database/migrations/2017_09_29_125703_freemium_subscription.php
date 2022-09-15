<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FreemiumSubscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Subscription table
         */
        \Schema::table('package', function(Blueprint $table) {
            $table->tinyInteger('is_freemium')->default(0);
            $table->string('freemium_recurrence_period', 5)->nullable();
            $table->tinyInteger('freemium_recurrence_value')->nullable();
            $table->unsignedTinyInteger('freemium_credits')->nullable();
        });

        /**
         * Package table
         */
        \Schema::table('subscription', function(Blueprint $table) {
            $table->tinyInteger('is_freemium')->default(0);
            $table->string('freemium_recurrence_period', 5)->nullable();
            $table->tinyInteger('freemium_recurrence_value')->nullable();
            $table->unsignedTinyInteger('freemium_credits')->nullable();
            $table->timestamp('freemium_credits_updated_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('subscription', function(Blueprint $table) {
            $table->dropColumn('is_freemium');
            $table->dropColumn('freemium_recurrence_period');
            $table->dropColumn('freemium_recurrence_value');
            $table->dropColumn('freemium_credits');
            $table->dropColumn('freemium_credits_updated_date');
        });

        \Schema::table('package', function(Blueprint $table) {
            $table->dropColumn('is_freemium');
            $table->dropColumn('freemium_credits');
            $table->dropColumn('freemium_recurrence_value');
            $table->dropColumn('freemium_recurrence_period');
        });
    }
}
