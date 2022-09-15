<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MailchimpSyncActivityFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mailchimp_sync', function (Blueprint $table) {
            $table->timestamp('l_general_activity_check_time')->after('l_general_status')->nullable()->comment('Time of the last activity check');
            $table->timestamp('l_general_activity_time')->after('l_general_activity_check_time')->nullable()->comment('The most resent time when a member opened any email');

            $table->timestamp('l_monetization_activity_check_time')->after('l_monetization_status')->nullable();
            $table->timestamp('l_monetization_activity_time')->after('l_monetization_activity_check_time')->nullable();

            $table->timestamp('l_newsletter_activity_check_time')->after('l_newsletter_status')->nullable();
            $table->timestamp('l_newsletter_activity_time')->after('l_newsletter_activity_check_time')->nullable();

            $table->index('l_general_time');
            $table->index('l_general_activity_check_time');
            $table->index('l_general_activity_time');

            $table->index('l_monetization_time');
            $table->index('l_monetization_activity_check_time');
            $table->index('l_monetization_activity_time');

            $table->index('l_newsletter_time');
            $table->index('l_newsletter_activity_check_time');
            $table->index('l_newsletter_activity_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('mailchimp_sync', function(Blueprint $table) {
            $table->dropColumn('l_general_activity_check_time');
            $table->dropColumn('l_general_activity_time');

            $table->dropColumn('l_monetization_activity_check_time');
            $table->dropColumn('l_monetization_activity_time');

            $table->dropColumn('l_newsletter_activity_check_time');
            $table->dropColumn('l_newsletter_activity_time');

            $table->dropIndex('l_general_time');
            $table->dropIndex('l_general_activity_check_time');
            $table->dropIndex('l_general_activity_time');

            $table->dropIndex('l_monetization_time');
            $table->dropIndex('l_monetization_activity_check_time');
            $table->dropIndex('l_monetization_activity_time');

            $table->dropIndex('l_newsletter_time');
            $table->dropIndex('l_newsletter_activity_check_time');
            $table->dropIndex('l_newsletter_activity_time');
        });
    }
}
