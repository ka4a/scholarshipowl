<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RecurrentScholarshipsSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("scholarship", function (Blueprint $table) {
            $table->boolean("is_recurrent")->default(false);
            $table->string('recurring_type', 5)->nullable();
            $table->tinyInteger("recurring_value", false, true)->nullable();
            $table->timestamp('start_date')->before('expire_date')->useCurrent();
            $table->integer("parent_scholarship_id", false, true)->nullable();
            $table->integer("current_scholarship_id", false, true)->nullable();

            $table->foreign('parent_scholarship_id')->references('scholarship_id')->on('scholarship');
            $table->foreign('current_scholarship_id')->references('scholarship_id')->on('scholarship');
        });
        Schema::table("profile", function (Blueprint $table) {
            $table->tinyInteger("recurring_application", false, true)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("profile", function (Blueprint $table) {
            $table->dropColumn("recurring_application");
        });
        Schema::table("scholarship", function (Blueprint $table) {
            $table->dropColumn("current_scholarship_id");
            $table->dropColumn("parent_scholarship_id");
            $table->dropColumn("start_date");
            $table->dropColumn("recurring_value");
            $table->dropColumn("recurring_type");
            $table->dropColumn("is_recurrent");
        });
    }
}
