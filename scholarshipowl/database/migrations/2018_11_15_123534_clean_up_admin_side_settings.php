<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CleanUpAdminSideSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('setting')->where(['name' => 'marketing.select_banner'])->delete();
        \DB::table('setting')->where(['name' => 'marketing.account_banner'])->delete();
        \DB::table('setting')->where(['name' => 'marketing.free-trial-fset'])->delete();
        \DB::table('setting')->where(['name' => 'memberships.free_trial_cancelled_text'])->delete();
        \DB::table('setting')->where(['name' => 'missions.general_message'])->delete();
        \DB::table('setting')->where(['name' => 'missions.tab_link_visible'])->delete();
        \DB::table('setting')->where(['name' => 'missions.tab_link_text'])->delete();
        \DB::table('setting')->where(['name' => 'missions.tab_mission_id'])->delete();
        \DB::table('setting')->where(['name' => 'packages.general_message'])->delete();
        \DB::table('setting')->where(['name' => 'packages.general_mobile_message'])->delete();
        \DB::table('setting')->where(['name' => 'paymentpopup.visible'])->delete();
        \DB::table('setting')->where(['name' => 'paymentpopup.headtextup'])->delete();
        \DB::table('setting')->where(['name' => 'paymentpopup.headtextdown'])->delete();
        \DB::table('setting')->where(['name' => 'paymentpopup.leftuptext'])->delete();
        \DB::table('setting')->where(['name' => 'paymentpopup.leftdowntext'])->delete();
        \DB::table('setting')->where(['name' => 'paymentpopup.rightuptext'])->delete();
        \DB::table('setting')->where(['name' => 'paymentpopup.rightdowntext'])->delete();
        \DB::table('setting')->where(['name' => 'paymentpopup.button'])->delete();
        \DB::table('setting')->where(['name' => 'zendesk.payment-popup.timeout'])->delete();
        \DB::table('setting')->where(['name' => 'paymentpopup.bottomTextEnabled'])->delete();
        \DB::table('setting')->where(['name' => 'paymentpopup.bottomText'])->delete();
        \DB::table('setting')->where(['name' => 'paymentpopup.defaultPaymentMethod'])->delete();
        \DB::table('setting')->where(['name' => 'payment.braintree.enabled'])->delete();
        \DB::table('setting')->where(['name' => 'premiuminbox.enabled'])->delete();
        \DB::table('setting')->where(['name' => 'premiuminbox.text'])->delete();
        \DB::table('setting')->where(['name' => 'premiuminbox.text_mobile'])->delete();
        \DB::table('setting')->where(['name' => 'register.inboxdollars'])->delete();
        \DB::table('setting')->where(['name' => 'register.inboxdollars_text'])->delete();
        \DB::table('setting')->where(['name' => 'register.toluna'])->delete();
        \DB::table('setting')->where(['name' => 'register.toluna_text'])->delete();
        \DB::table('setting')->where(['name' => 'freeTrial.redirectAfterCancel'])->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
