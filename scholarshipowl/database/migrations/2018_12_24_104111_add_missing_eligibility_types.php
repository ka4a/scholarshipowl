<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingEligibilityTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("
            ALTER TABLE eligibility 
            MODIFY COLUMN `type` ENUM('required', 'value', 'less_than', 'greater_than', 'between', 'not', 'in', 'greater_than_or_equal', 'less_than_or_equal', 'nin')  
            default 'required' not null
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
            ALTER TABLE eligibility 
            MODIFY COLUMN `type` ENUM('required', 'value', 'less_than', 'greater_than', 'between', 'not', 'in') 
            default 'required' not null
        ");
    }
}
