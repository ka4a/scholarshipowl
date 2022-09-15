<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStateNameField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $field = new \App\Entity\Field();
        $field->setId(\App\Entity\Field::STATE_FREE_TEXT);
        $field->setName("State (free text)");

        \EntityManager::persist($field);
        \EntityManager::flush();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $field = \EntityManager::find(\App\Entity\Field::class, \App\Entity\Field::STATE_FREE_TEXT);

        \EntityManager::remove($field);
        \EntityManager::flush();
    }
}
