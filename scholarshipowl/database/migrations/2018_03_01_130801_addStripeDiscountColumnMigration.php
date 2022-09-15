<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStripeDiscountColumnMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('package', function(Blueprint $table) {
            $table->string('stripe_discount_id')->after('stripe_plan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('package', function(Blueprint $table) {
            $table->dropColumn('stripe_discount_id');
        });
    }
}
