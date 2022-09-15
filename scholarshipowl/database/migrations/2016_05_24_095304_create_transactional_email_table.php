<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionalEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("transactional_email", function (Blueprint $table){
            $table->increments("transactional_email_id")->comment("Primary key");
            $table->string("event_name", 60)->comment("Name of the event on which email is sent");
            $table->string("template_name", 60)->comment("Mandrill template slug")->unique();
            $table->string("from_email", 128)->default("ScholarshipOwl@scholarshipowl.com")->comment("From email address");
            $table->string("from_name", 128)->comment("From name");
            $table->string("subject", 256)->nullable()->comment("MEmail subject");
            $table->smallInteger("sending_cap")->default(0)->unsigned()->comment("Maximum amount of mails to be sent in given period");
            $table->enum("cap_period", ["day", "week", "month", "year"])->default("day")->comment("Period for which capping is set");
            $table->boolean("active")->default(false)->comment("Should this type of emails be sent");
        });

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Refer A Friend");
        $transactionalEmail->setTemplateName("refer-a-friend");
        $transactionalEmail->setFromName("ScholarshipOwl Site Account");
        $transactionalEmail->setSubject("Your friend invited you to ScholarshipOwl");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Successful non recurrent deposit");
        $transactionalEmail->setTemplateName("mandrill-successful-non-recurrent-deposit");
        $transactionalEmail->setFromName("ScholarshipOwl Site Package Purchase");
        $transactionalEmail->setSubject("ScholarshipOwl - Successful Payment");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("1st successful recurrent deposit");
        $transactionalEmail->setTemplateName("mandrill-1st-successful-deposit");
        $transactionalEmail->setFromName("ScholarshipOwl Site Package Purchase");
        $transactionalEmail->setSubject("ScholarshipOwl - Successful Payment");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Successful repeat deposit");
        $transactionalEmail->setTemplateName("mandrill-successful-repeat-deposit");
        $transactionalEmail->setFromName("ScholarshipOwl Site Package Purchase");
        $transactionalEmail->setSubject("ScholarshipOwl - Successful Payment");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("1st failed deposit");
        $transactionalEmail->setTemplateName("mandrill-1st-failed-deposit");
        $transactionalEmail->setFromName("ScholarshipOwl Site Package Purchase");
        $transactionalEmail->setSubject("ScholarshipOwl - Failed Payment");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Failed recurrent deposit");
        $transactionalEmail->setTemplateName("mandrill-failed-recurrent-deposit");
        $transactionalEmail->setFromName("ScholarshipOwl Site Package Purchase");
        $transactionalEmail->setSubject("ScholarshipOwl - Failed Payment");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Membership awarded one time billing");
        $transactionalEmail->setTemplateName("mandrill-membership-awarded-one-time-billing");
        $transactionalEmail->setFromName("ScholarshipOwl Site Membership");
        $transactionalEmail->setSubject("ScholarshipOwl - Membership Awarded");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Membership awarded repeat billing");
        $transactionalEmail->setTemplateName("mandrill-membership-awarded-repeat-billing");
        $transactionalEmail->setFromName("ScholarshipOwl Site Membership");
        $transactionalEmail->setSubject("ScholarshipOwl - Membership Awarded");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Membership renewed repeat billing");
        $transactionalEmail->setTemplateName("mandrill-membership-renewed-repeat-billing");
        $transactionalEmail->setFromName("ScholarshipOwl Site Membership");
        $transactionalEmail->setSubject("ScholarshipOwl - Membership Awarded");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Membership expired");
        $transactionalEmail->setTemplateName("mandrill-membership-expired");
        $transactionalEmail->setFromName("ScholarshipOwl Site Membership");
        $transactionalEmail->setSubject("ScholarshipOwl - Membership Expired");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Applications sent: Scholarship application sent successfully");
        $transactionalEmail->setTemplateName("mandrill-application-s-sent-succesfully");
        $transactionalEmail->setFromName("ScholarshipOwl");
        $transactionalEmail->setSubject("ScholarshipOwl - Applications Sent");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Selected Application(s) expire within 48h");
        $transactionalEmail->setTemplateName("mandrill-application-s-expire-within-48h");
        $transactionalEmail->setFromName("ScholarshipOwl");
        $transactionalEmail->setSubject("ScholarshipOwl - Applications Expiring");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Forgot Password");
        $transactionalEmail->setTemplateName("forgot-password");
        $transactionalEmail->setFromName("ScholarshipOwl Site Account");
        $transactionalEmail->setSubject("ScholarshipOwl - Password Reset");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Purchase Package");
        $transactionalEmail->setTemplateName("purchase-package");
        $transactionalEmail->setFromName("ScholarshipOwl Site Package Purchase");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Account Update");
        $transactionalEmail->setTemplateName("account-update");
        $transactionalEmail->setFromName("ScholarshipOwl Site Account");
        $transactionalEmail->setSubject("ScholarshipOwl - Account Changes Saved");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Package Exhausted");
        $transactionalEmail->setTemplateName("package-exhausted");
        $transactionalEmail->setFromName("ScholarshipOwl Site Package Exhausted");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Mailbox Welcome");
        $transactionalEmail->setSubject("Welcome to your ScholarshipOwl application inbox");
        $transactionalEmail->setTemplateName("mailbox-welcome");
        $transactionalEmail->setFromEmail("Owl@ScholarshipOwl.com");
        $transactionalEmail->setFromName("ScholarshipOwl Mailbox");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Change Password");
        $transactionalEmail->setSubject("Password Changed");
        $transactionalEmail->setTemplateName("change-password");
        $transactionalEmail->setFromName("ScholarshipOwl Site Account");

        EntityManager::persist($transactionalEmail);

        EntityManager::flush();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("transactional_email");
    }
}
