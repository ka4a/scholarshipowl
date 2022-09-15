<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PushNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notifications', function(Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 100);
            $table->boolean('is_active');
            $table->unique('slug');
        });

        // Long time no see
        DB::table("push_notifications")->insert(array(
            "slug" => "long-time-no-see",
            "is_active" => true
        ));

        // New email
        DB::table("push_notifications")->insert(array(
            "slug" => "new-email",
            "is_active" => true
        ));

        // New scholarship matches
        DB::table("push_notifications")->insert(array(
            "slug" => "new-scholarship-matches",
            "is_active" => true
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('push_notifications');
    }
}
