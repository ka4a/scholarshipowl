<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PopupRedirectRuleSetNewField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('popup', function (Blueprint $table) {
            $table->unsignedInteger('rule_set_id')->nullable();
            $table->foreign('rule_set_id', 'fk_redirect_rules_set_id')
                ->references('redirect_rules_set_id')->on('redirect_rules_set');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('popup', function (Blueprint $table) {
            $table->dropForeign('fk_redirect_rules_set_id');
            $table->dropColumn('rule_set_id');
        });
    }
}
