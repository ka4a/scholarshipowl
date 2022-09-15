<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnrolledEligibility extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('field')->insert([['field_id' => \App\Entity\Field::ENROLLED, 'name' => 'Enrolled in College']]);
        \DB::statement('delete from field where field_id in (49, 50, 51, 52, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63)');

        \DB::statement("
            ALTER TABLE eligibility 
            MODIFY COLUMN `type` ENUM('required', 'value', 'less_than', 'greater_than', 'between', 'not', 'in', 'greater_than_or_equal', 'less_than_or_equal', 'nin', 'boolean')  
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
        \DB::table('field')->where(['field_id' => \App\Entity\Field::ENROLLED])->delete();
        \DB::table('field')->insert([['field_id' => 49, 'name' => 'Accept Confirmation 2']]);
        \DB::table('field')->insert([['field_id' => 50, 'name' => 'Accept Confirmation 3']]);
        \DB::table('field')->insert([['field_id' => 51, 'name' => 'Accept Confirmation 4']]);
        \DB::table('field')->insert([['field_id' => 52, 'name' => 'Accept Confirmation 5']]);
        \DB::table('field')->insert([['field_id' => 54, 'name' => 'Hidden Field 1']]);
        \DB::table('field')->insert([['field_id' => 55, 'name' => 'Hidden Field 2']]);
        \DB::table('field')->insert([['field_id' => 56, 'name' => 'Hidden Field 3']]);
        \DB::table('field')->insert([['field_id' => 57, 'name' => 'Hidden Field 4']]);
        \DB::table('field')->insert([['field_id' => 58, 'name' => 'Hidden Field 5']]);
        \DB::table('field')->insert([['field_id' => 59, 'name' => 'Static Field 1']]);
        \DB::table('field')->insert([['field_id' => 60, 'name' => 'Static Field 2']]);
        \DB::table('field')->insert([['field_id' => 61, 'name' => 'Static Field 3']]);
        \DB::table('field')->insert([['field_id' => 62, 'name' => 'Static Field 4']]);
        \DB::table('field')->insert([['field_id' => 63, 'name' => 'Static Field 5']]);

        \DB::statement("
            ALTER TABLE eligibility 
            MODIFY COLUMN `type` ENUM('required', 'value', 'less_than', 'greater_than', 'between', 'not', 'in', 'greater_than_or_equal', 'less_than_or_equal', 'nin') 
            default 'required' not null
        ");
    }
}


