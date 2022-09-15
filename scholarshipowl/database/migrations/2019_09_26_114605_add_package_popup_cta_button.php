<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackagePopupCtaButton extends Migration
{
    public function up()
    {
        \Schema::table('package', function(Blueprint $table) {
            $table->string('popup_cta_button', 255)->nullable();
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
            $table->dropColumn('popup_cta_button');
        });
    }
}
