<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountEligibleScholarshipsCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("account_eligible_scholarships_count", function (Blueprint $table) {
            $table->integer("account_id", false, true)->unique();
            $table->integer("scholarship_count", false, true);
            $table->timestamps();

            $table->primary("account_id");
            $table->foreign("account_id")->references("account_id")->on("account");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("account_eligible_scholarships_count");
    }
}
