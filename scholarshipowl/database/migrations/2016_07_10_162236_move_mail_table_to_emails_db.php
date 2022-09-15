<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveMailTableToEmailsDb extends Migration
{
    /**
     * @var string
     */
    protected $connection = 'emails';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::dropIfExists('email_attachment');
        \Schema::dropIfExists('email');

        \Schema::connection($this->getConnection())->dropIfExists('email_attachment');
        \Schema::connection($this->getConnection())->dropIfExists('email');

        \Schema::connection($this->getConnection())->create('email', function(Blueprint $table) {
            $table->increments('email_id');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('scholarship_id')->nullable();
            $table->string('folder');
            $table->string('message_id');
            $table->string('subject', 2045);
            $table->string('body', 65000);
            $table->string('sender', 1023);
            $table->string('recipient', 1023);
            $table->boolean('is_read')->default(0);
            $table->timestamp('date');

            $table->index('account_id');
            $table->index(['account_id', 'folder'], 'account_id_folder_inx');
            $table->index(['account_id', 'folder', 'is_read'], 'account_id_folder_is_read_inx');
            $table->unique(['account_id', 'message_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::connection($this->getConnection())->dropIfExists('email');
    }
}
