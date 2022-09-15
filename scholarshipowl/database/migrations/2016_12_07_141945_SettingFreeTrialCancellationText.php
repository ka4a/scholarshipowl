<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Entity\Setting;
use App\Entity\Subscription;

class SettingFreeTrialCancellationText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var Setting $activeText */
        $activeText = \Setting::get('scholarships.active_text');
        $activeText->setName(Subscription::SETTING_MEMBERSHIP_ACTIVE_TEXT)
            ->setGroup('Memberships');

        /** @var Setting $cancelText */
        $cancelText = \Setting::get('scholarships.cancell_text');
        $cancelText->setName(Subscription::SETTING_MEMBERSHIP_CANCELLED_TEXT)
            ->setGroup('Memberships');

        \Setting::create(
            Setting::TYPE_TEXT,
            'Memberships',
            Subscription::SETTING_MEMBERSHIP_FREE_TRIAL_ACTIVE_TEXT,
            'Memberships free trial active text',
            $activeText->getValue()
        );
        \Setting::create(
            Setting::TYPE_TEXT,
            'Memberships',
            Subscription::SETTING_MEMBERSHIP_FREE_TRIAL_CANCELED_TEXT,
            'Memberships free trial cancelled text',
            'Your Trial Membership has been cancelled and expires [[expirationDate]]. Make sure to make the most of the membership while it remains active. At the expiry of your trial, there will be no charge.'
        );

        \EntityManager::flush();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Setting::delete(Subscription::SETTING_MEMBERSHIP_FREE_TRIAL_ACTIVE_TEXT);
        \Setting::delete(Subscription::SETTING_MEMBERSHIP_FREE_TRIAL_CANCELED_TEXT);
        \Setting::get(Subscription::SETTING_MEMBERSHIP_ACTIVE_TEXT)
            ->setName('scholarships.active_text')
            ->setGroup('Scholarships');
        \Setting::get(Subscription::SETTING_MEMBERSHIP_CANCELLED_TEXT)
            ->setName('scholarships.cancell_text')
            ->setGroup('Scholarships');
        \EntityManager::flush();
    }
}
