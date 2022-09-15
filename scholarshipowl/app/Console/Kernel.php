<?php namespace App\Console;

use App\Console\Commands;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\CacheSchedulingMutex;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Scheduling\SchedulingMutex;
use Illuminate\Container\Container;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ChangeAccountsUsernames::class,
        Commands\SubscriptionsAward::class,
        Commands\CreateAccountsMailboxes::class,
        Commands\StatisticDaily::class,
        Commands\ApplicationSend::class,
        Commands\ApplicationExpireEmails::class,
        Commands\PaymentMessageResubmit::class,
        Commands\AccountsCache::class,
        Commands\TestReadMailbox::class,
        Commands\SubmissionSend::class,
        Commands\ReferralAward::class,
        Commands\CollegeRecruiterAutoupload::class,
        Commands\QueuePaymentMessageRetry::class,
        Commands\SubscriptionInfo::class,
        Commands\MailTest::class,
        Commands\UloopExport::class,
        Commands\UpdateCappexCollegeList::class,
        Commands\SubmitSimpleTuitionLeads::class,
        Commands\ZendeskImportUsers::class,
        Commands\CollegeFactualExport::class,
        Commands\LoopExport::class,
        Commands\EligibleScholarshipsEmails::class,
        Commands\GetSupercollegeEligibility::class,
        Commands\SubmitDoublePositiveLeads::class,
        Commands\ScholarshipsRecurrenceApply::class,
        Commands\ExportInstallations::class,
        Commands\AccountExport::class,
        Commands\AccountNotificationDaily::class,
        Commands\ClearBeanstalkdQueueCommand::class,
        Commands\OneSignalUserImport::class,
        Commands\SubmitChristianConnectorLeads::class,
        Commands\ScholarshipRandomImage::class,
        Commands\ScholarshipMaintain::class,
        Commands\LoopExportPayments::class,
        Commands\SubscriptionMaintain::class,
        Commands\RemoveInactiveSubmissions::class,
        Commands\AccountHardDelete::class,
        Commands\AccountTestDelete::class,
        Commands\CoregResubmitByStatus::class,
		Commands\ResubmitErrorSubmittionSubmissions::class,

        Commands\BillingAddressUpdating::class,
        Commands\SubmitVinylCoreg::class,
        Commands\EdvisorsExport::class,
        Commands\UnsubscribedUpdated::class,
        Commands\LoginTokenCleanup::class,
        Commands\ApplicationResendFailed::class,

        Commands\PubSubAccountsUpdate::class,
        Commands\CommandRunner::class,
        Commands\EligibilityCacheClearNonActive::class,
        Commands\SubmitGossamerScienceCoreg::class,
        Commands\PushNotifications::class,
    ];

    /**
     * @inheritdoc
     */
    protected function defineConsoleSchedule()
    {
        $this->app->bind(SchedulingMutex::class, function() {
            $container = Container::getInstance();
            $mutex = $container->make(CacheSchedulingMutex::class);
            return $mutex->useStore('redisShared');
        });

        parent::defineConsoleSchedule();
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $timezone = timezone();
        $defaultNotificationTime = Carbon::createFromFormat('H:i', '10:00', 'CET');
        $schedule->useCache('redisShared');

        $schedule->command('account:notification:daily')
            ->dailyAt(Carbon::instance($defaultNotificationTime)->setTimezone($timezone)->format('H:i'))
            ->onOneServer();

        $schedule->command('scholarship:maintain')
            ->withoutOverlapping()
            ->everyTenMinutes()
            ->onOneServer();

        $schedule->command('scholarships:recurrence-apply')
            ->dailyAt('6:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/recurrent_scholarships_apply.log'))
            ->onOneServer();

        $schedule->command('subscription:maintain')
            ->dailyAt(
                Carbon::instance(
                    Carbon::createFromFormat('H:i', '00:00', 'CET')
                )->setTimezone($timezone)->format('H:i')
            )->onOneServer();

        $schedule->command('unsubscribed:update')
            ->withoutOverlapping()
            ->hourly()
            ->onOneServer();

        $schedule->command('account:hard-delete --softDeletedDaysAgo=7')
            ->dailyAt('7:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/account_delete.log'))
            ->onOneServer();

        $schedule->command('account:test-delete --createdDaysAgo=7')
            ->dailyAt('7:10')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/account_test_delete.log'))
            ->onOneServer();

        $schedule->command('account:login-token-cleanup')
            ->dailyAt('8:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/login_token_cleanup.log'))
            ->onOneServer();

        if (is_production()) {

            /**
             * @deprecated
             */
//            $schedule->command('payment:update-address braintree')
//                ->dailyAt(
//                    Carbon::instance(
//                        Carbon::createFromFormat('H:i', '00:01', 'CET')
//                    )->format('H:i')
//                )
//                ->onOneServer();

            /**
             * @deprecated
             */
//            $schedule->command('payment:update-address stripe')
//                ->dailyAt(
//                    Carbon::instance(
//                        Carbon::createFromFormat('H:i', '00:01', 'CET')
//                    )->format('H:i')
//                )
//                ->onOneServer();

            /**
             * Scholarship emails
             */
            // $schedule->command('scholarships:email')->at('16:30')
            //     ->appendOutputTo(storage_path('logs/cron_loop_export_payments.log'))
            //     ->onOneServer();

            /**
             * Scholarships applications
             */
            $schedule->command('application:send')->everyMinute()
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/cron_application_send.log'))
                ->onOneServer();

            $schedule->command('application:resend')->hourly()
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/cron_application_send.log'))
                ->onOneServer();
            /**
             * Daily statistics tasks
             */
            $schedule->command('statistic:daily --date=yesterday')->at('00:01')
                ->appendOutputTo(storage_path('logs/cron_statistics_daily.log'))
                ->onOneServer();

            /**
             * Other
             */
            $schedule->command('referral:award')->at('07:00')
                ->appendOutputTo(storage_path('logs/cron_referral_award.log'))
                ->onOneServer();

            /**
             * Co-regs
             */
            $schedule->command('submission:send')->everyTenMinutes()
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/cron_submissions.log'))
                ->onOneServer();

            $schedule->command('submission:simpletuition')->everyTenMinutes()
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/cron_submission_simpletuition.log'))
                ->onOneServer();

            $schedule->command('submission:christianconnector')->at('02:00')
                ->appendOutputTo(storage_path('logs/cron_submission_christianconnector.log'))
                ->onOneServer();

//            $schedule->command('submission:gossamerscience')->everyFiveMinutes()
//                ->appendOutputTo(storage_path('logs/cron_submission_gossamerscience.log'))
//                ->onOneServer();
            /**
             * Disable by Kenny's request
             */

//            $schedule->command('submission:vinyl')->at('02:00')
//                ->appendOutputTo(storage_path('logs/cron_submission_vinyl.log'))
//                ->onOneServer();

            /**
             * disable by Kanny's request
             */
//            $schedule->command('collegeRecruiter:autoupload')->at('05:00')
//                ->appendOutputTo(storage_path('logs/cron_college_recruiter.log'))
//                ->onOneServer();

//            $schedule->command('edvisors:daily')->at('04:00')
//                ->appendOutputTo(storage_path('logs/edvisors_daily.log'))
//                ->onOneServer();

            $schedule->command('collegeFactual:export')->at('05:00')
                ->appendOutputTo(storage_path('logs/cron_college_factual.log'))
                ->onOneServer();

            $schedule->command('uloop:export')->at('01:00')
                ->appendOutputTo(storage_path('logs/cron_uloop_export.log'))
                ->onOneServer();

            $schedule->command('submission:remove-inactive')->at('04:00')
                ->onOneServer();

            $schedule->command('eligibilitycache:clear-nonactive')->at('03:00')
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/eligibility_cache_cleanup.log'))
                ->onOneServer();

            /**
             * Loop export
             */
            /*$schedule->command('loop:export')->at('12:00')
                ->appendOutputTo(storage_path('logs/cron_loop_export.log'))->onOneServer();*/

            /*$schedule->command('loop:export-payments')->at('01:00')
                ->appendOutputTo(storage_path('logs/cron_loop_export_payments.log'))->onOneServer();*/


            $schedule->command('pushnotification:send')->weeklyOn(3, '19:00')
                ->onOneServer();
        }
    }
}
