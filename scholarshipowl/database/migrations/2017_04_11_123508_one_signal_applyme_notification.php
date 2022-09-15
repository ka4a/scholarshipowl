<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OneSignalApplymeNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('notification_type')->insert([
            [
                'id'   => 4,
                'name' => 'Scholarships update'
            ]]);

        \DB::table('onesignal_notification')->insert([
            [
                'active'     => 1,
                'type'       => \App\Entity\NotificationType::NOTIFICATION_SCHOLARSHIPS_UPDATE,
                'app'        => \App\Entity\OnesignalNotification::APP_MOBILE,
                'title'      => "It’s meant to be!",
                'content'    => '[[amount]] new scholarships matches were added since the last time you checked. Swipe right to find more details.',
                'cap_amount' => 3,
                'cap_value'  => 1,
                'cap_type'   => \App\Entity\OnesignalNotification::CAP_PERIOD_TYPE_DAY,
            ],
            [
                'active'     => 1,
                'type'       => \App\Entity\NotificationType::NOTIFICATION_NEW_EMAIL,
                'app'        => \App\Entity\OnesignalNotification::APP_MOBILE,
                'title'      => 'New message in your mailbox',
                'content'    => '[[email_subject]]',
                'cap_amount' => 3,
                'cap_value'  => 1,
                'cap_type'   => \App\Entity\OnesignalNotification::CAP_PERIOD_TYPE_HOUR,
            ]
        ]);

        \DB::table('onesignal_notification')->insert([
            'active'     => 1,
            'type'       => \App\Entity\NotificationType::NOTIFICATION_LONG_TIME_NOT_SEE,
            'app'        => \App\Entity\OnesignalNotification::APP_MOBILE,
            'cap_amount' => 1,
            'cap_value'  => 1,
            'cap_type'   => \App\Entity\OnesignalNotification::CAP_PERIOD_TYPE_WEEK,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('onesignal_notification')->where('app', \App\Entity\OnesignalNotification::APP_MOBILE)->delete();
        \DB::table('notification_type')->where('id', 4)->delete();
    }
}
