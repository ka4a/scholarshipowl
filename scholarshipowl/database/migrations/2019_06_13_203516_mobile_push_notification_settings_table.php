<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MobilePushNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("mobile_push_notification_settings", function (Blueprint $table){
            $table->increments("push_notification_id");
            $table->string("notification_name");
            $table->string("event_name");
            $table->boolean("active")->default(false);
        });

        $setting = new \App\Entity\Marketing\MobilePushNotificationSettings(
             'Proved winner event. Use for trigger SCHOLARSHIP_USER_WON notification',
                'App\Events\Scholarship\ScholarshipProvedWinnerEvent',
                true
        );
        EntityManager::persist($setting);

        $setting = new \App\Entity\Marketing\MobilePushNotificationSettings(
            'Potential winner event. Use for trigger SCHOLARSHIP_USER_AWARDED or SCHOLARSHIP_WINNER_CHOSEN notifications',
            'App\Events\Scholarship\ScholarshipPotentialWinnerEvent',
            true
        );
        EntityManager::persist($setting);


        $setting = new \App\Entity\Marketing\MobilePushNotificationSettings(
            'Disqualified winner event. Use for trigger SCHOLARSHIP_USER_MISSED notification.',
            'App\Events\Scholarship\ScholarshipDisqualifiedWinnerEvent',
            true
        );
        EntityManager::persist($setting);

        $setting = new \App\Entity\Marketing\MobilePushNotificationSettings(
            'New email events',
            'App\Events\Email\NewEmailEvent',
            true
        );
        EntityManager::persist($setting);

        $setting = new \App\Entity\Marketing\MobilePushNotificationSettings(
            'New Match events',
            'App\Events\Firebase\NewMatchEvent',
            false
        );
        EntityManager::persist($setting);
        EntityManager::flush();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("mobile_push_notification_settings");
    }
}
