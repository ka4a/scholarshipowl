<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Entity\Setting;

class SettingFreeTrialCancellationTextPopup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Setting::create(
            Setting::TYPE_TEXT,
            Setting::GROUP_MEMBERSHIPS,
            Setting::SETTING_FREE_TRIAL_CANCEL_SUBSCRIPTION,
            'Cancellation popup text',
            '<p>Your trial membership will become inactive immediately upon trial cancellation and no applications will be submitted thereafter.</p>' .
            '<p>Your trial is set to expire and convert into a PREMIUM membership [[subscription_free_trial_end_date]]. Get the most out of your [[eligibility_count]] Scholarship matches by leveraging until then.</p>'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Setting::delete(Setting::SETTING_FREE_TRIAL_CANCEL_SUBSCRIPTION);
    }
}
