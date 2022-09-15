<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteScholarshipsRedirectMobile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('setting')->where(['name' => 'scholarships.redirect_mobile'])->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('setting')->insert([
            'group' => 'Scholarships',
            'name' => 'scholarships.redirect_mobile',
            'title' => 'Scholarships page to display to mobile users',
            'value' => '"select"',
            'type' => 'select',
            'default_value' => '"select"',
            'options' => '{"default":"URL Visited","select":"Select","scholarships":"Scholarships"}',
        ]);
    }
}
