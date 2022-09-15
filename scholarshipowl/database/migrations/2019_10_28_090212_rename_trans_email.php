<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTransEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            \DB::statement("
                UPDATE transactional_email
                SET template_name = 'membership-expired'
                WHERE template_name = 'mandrill-membership-expired'            
            ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            \DB::statement("
                UPDATE transactional_email
                SET template_name = 'mandrill-membership-expired'
                WHERE template_name = 'membership-expired'            
            ");
    }
}
