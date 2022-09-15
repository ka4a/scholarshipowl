<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PasswordResetEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $email = new \App\Entity\TransactionalEmail(
            'Password reset',
            \App\Services\PubSub\TransactionalEmailService::APP_PASSWORD_RESET,
            'ScholarshipOwl',
            'Password reset link',
            'info@scholarshipowl.com',
            true
        );
        EntityManager::persist($email);
        EntityManager::flush($email);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('transactional_email')
            ->where('template_name', \App\Services\PubSub\TransactionalEmailService::APP_PASSWORD_RESET)
            ->delete();
    }
}
