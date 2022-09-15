<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountOneSignalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();

        \Schema::create('notification_type', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        \DB::table('notification_type')->insert([
            [
                'id' => \App\Entity\NotificationType::NOTIFICATION_NEW_ELIGIBLE_SCHOLARSHIP,
                'name' => 'New Eligible Scholarship',
            ],
            [
                'id' => \App\Entity\NotificationType::NOTIFICATION_NEW_EMAIL,
                'name' => 'New Email',
            ],
            [
                'id' => \App\Entity\NotificationType::NOTIFICATION_LONG_TIME_NOT_SEE,
                'name' => 'Long time not see',
            ],
        ]);

        /*
        \Schema::create('onesignal_app', function(Blueprint $table) {
            $table->string('name');
            $table->primary('name');

            $table->string('id', 36);
            $table->index('id');

            $table->string('subdomain');
            $table->string('api_key');
        });

        \DB::table('onesignal_app')->insert([
            [
                'id' => '45a416c7-6172-4c94-bf17-7b619906c30c',
                'name' => 'ScholarshipOwl Web',
                'subdomain' => 'scholarship',
                'api_key' => 'NThlZTYxYzMtMzM2Ni00ODZkLTkzODAtM2FkMzI2MzAyMzNh',
            ],
            [
                'id' => 'da55384e-3fc9-49fa-a8dc-07c294397135',
                'name' => 'ScholarshipOwl Web Dev',
                'subdomain' => 'scholarship-dev',
                'api_key' => 'MTMyYzYwNTgtN2E2My00OTRiLWI0NjUtYzM2YjY5MjhkYmM1',
            ],
            [
                'id' => 'e49cd39e-af0b-4667-871a-aa8d06865cf4',
                'name' => 'ScholarshipOwl Web Stg',
                'subdomain' => 'scholarship-stg',
                'api_key' => 'YjVlZDlkMjktMmFhMy00OWFmLTg0MWEtNDU2ZTAzY2EzYzEx',
            ],
        ]);
        */

        \Schema::create('onesignal_account', function(Blueprint $table) {
            $table->unsignedInteger('account_id');
            $table->string('user_id', 36);
            $table->string('app', 8);
//            $table->string('app_id', 36);

            $table->foreign('account_id')->references('account_id')->on('account');
//            $table->foreign('app_id')->references('id')->on('onesignal_app');

//            $table->primary(['account_id', 'user_id', 'app_id']);
            $table->primary(['account_id', 'user_id', 'app']);
            $table->index(['account_id', 'user_id']);
            $table->index('account_id');
            $table->index('user_id');
            $table->index('app');

            $table->timestamps();
        });

        \Schema::create('onesignal_notification', function(Blueprint $table) {
            $table->unsignedInteger('type');
            $table->foreign('type')->references('id')->on('notification_type');
            $table->string('app', 8);

//            $table->string('app_id', 36);
//            $table->foreign('app_id')->references('id')->on('onesignal_app');

            $table->primary(['type', 'app']);
            $table->index(['app', 'type']);

            $table->string('template_id')->nullable();
            $table->string('title')->nullable();
            $table->string('content')->nullable();

            $table->unsignedSmallInteger('active')->default(0);

            $table->unsignedInteger('cap_amount')->default(0);

            $table->unsignedInteger('cap_value')->default(0);
            $table->string('cap_type')->nullable();

            $table->unsignedInteger('delay_value')->default(0);
            $table->string('delay_type')->nullable();

            $table->timestamps();
        });

        \DB::table('onesignal_notification')->insert([
            [
                'type' => \App\Entity\NotificationType::NOTIFICATION_NEW_ELIGIBLE_SCHOLARSHIP,
                'app' => \App\Entity\OnesignalNotification::APP_WEB,
                'cap_amount' => 3,
                'cap_value'  => 1,
                'cap_type'   => \App\Entity\OnesignalNotification::CAP_PERIOD_TYPE_DAY,
            ],
            [
                'type' => \App\Entity\NotificationType::NOTIFICATION_NEW_EMAIL,
                'app' => \App\Entity\OnesignalNotification::APP_WEB,
                'cap_amount' => 1,
                'cap_value'  => 1,
                'cap_type'   => \App\Entity\OnesignalNotification::CAP_PERIOD_TYPE_HOUR,
            ],
            [
                'type' => \App\Entity\NotificationType::NOTIFICATION_LONG_TIME_NOT_SEE,
                'app' => \App\Entity\OnesignalNotification::APP_WEB,
                'cap_amount' => 1,
                'cap_value'  => 1,
                'cap_type'   => \App\Entity\OnesignalNotification::CAP_PERIOD_TYPE_WEEK,
            ],
        ]);

        \Schema::create('onesignal_notification_sent', function(Blueprint $table) {
            $table->increments('id');
//            $table->string('app_id', 36);
            $table->string('app', 8);
            $table->string('user_id', 36);
            $table->unsignedInteger('type_id');

            $table->foreign('type_id')->references('id')->on('notification_type');
//            $table->foreign('app_id')->references('id')->on('onesignal_app');

            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('onesignal_notification_sent');
        \Schema::dropIfExists('onesignal_notification');
        \Schema::dropIfExists('onesignal_account');
        \Schema::dropIfExists('onesignal_app');

        \Schema::dropIfExists('notification_type');
    }
}
