<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SettingScholarshipRedirect extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('setting')->insert([
            'group' => 'Scholarships',
            'name' => 'scholarships.redirect_members',
            'title' => 'Scholarships page to display to members',
            'value' => '"scholarships"',
            'type' => 'select',
            'default_value' => '"scholarships"',
            'options' => '{"default":"URL Visited","select":"Select","scholarships":"Scholarships"}',
        ]);

        \DB::table('setting')->insert([
            'group' => 'Scholarships',
            'name' => 'scholarships.redirect_free',
            'title' => 'Scholarships page to display to free users',
            'value' => '"select"',
            'type' => 'select',
            'default_value' => '"select"',
            'options' => '{"default":"URL Visited","select":"Select","scholarships":"Scholarships"}',
        ]);

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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('setting')->where(['name' => 'scholarships.redirect_members'])->delete();

        \DB::table('setting')->where(['name' => 'scholarships.redirect_free'])->delete();

        \DB::table('setting')->where(['name' => 'scholarships.redirect_mobile'])->delete();
    }
}
