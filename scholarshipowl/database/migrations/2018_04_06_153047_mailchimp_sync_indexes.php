<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MailchimpSyncIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mailchimp_sync', function(Blueprint $table)
        {
            $table->index('l_general_status');
            $table->index('l_paid_status');
            $table->index('l_monetization_status');
            $table->index('l_conversion_status');
            $table->index('l_owl_status');
            $table->index('l_newsletter_status');
            $table->index('l_special_offers_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropIndex('l_general_status');
        $table->dropIndex('l_paid_status');
        $table->dropIndex('l_monetization_status');
        $table->dropIndex('l_conversion_status');
        $table->dropIndex('l_owl_status');
        $table->dropIndex('l_newsletter_status');
        $table->dropIndex('l_special_offers_status');
    }
}
