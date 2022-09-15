<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApplicationsFeatureContentSetFieldAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Subscription table
         */
        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->string('application_sent_title');
            $table->text('application_sent_description');
            $table->text('application_sent_content');
            $table->string('no_credits_title');
            $table->text('no_credits_description');
            $table->text('no_credits_content');
            $table->text('upgrade_block_text');
            $table->string('upgrade_block_link_upgrade');
            $table->string('upgrade_block_link_vip');
        });

        $applicationSentTitle = 'Well done!';
        $applicationSentDescription = 'Your application was successfully sent!';
        $applicationSentContent = '<p>You have reached your daily application limit</p><p>Come back tomorrow for more!</p>';

        $noCreditsTitle = 'No credits!';
        $noCreditsDescription = 'Your application was not sent!';
        $noCreditsContent = '<p>You have reached your daily application limit</p><p>Come back tomorrow for more!</p>';

        \DB::table('feature_content_set')->update([
            'application_sent_title' => $applicationSentTitle,
            'application_sent_description' => $applicationSentDescription,
            'application_sent_content' => $applicationSentContent,

            'no_credits_title' => $noCreditsTitle,
            'no_credits_description' => $noCreditsDescription,
            'no_credits_content' => $noCreditsContent,

            'upgrade_block_text' => '<p>Want to submit unlimited applications and remove ads?</p><p>Upgrade to one of our memberships today!</p>',
            'upgrade_block_link_upgrade' => 'upgrade now',
            'upgrade_block_link_vip' => 'GET VIP',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->dropColumn('application_sent_title');
            $table->dropColumn('application_sent_description');
            $table->dropColumn('application_sent_content');
            $table->dropColumn('no_credits_title');
            $table->dropColumn('no_credits_description');
            $table->dropColumn('no_credits_content');
            $table->dropColumn('upgrade_block_text');
            $table->dropColumn('upgrade_block_link_upgrade');
            $table->dropColumn('upgrade_block_link_vip');
        });
    }
}
