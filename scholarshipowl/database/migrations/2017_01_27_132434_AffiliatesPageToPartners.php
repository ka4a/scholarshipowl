<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AffiliatesPageToPartners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('cms')->where(['page' => 'affiliates'])->update(['url' => '/partners', 'page' => 'partners']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('cms')->where(['page' => 'partners'])->update(['url' => '/affiliates', 'page' => 'affiliates']);
    }
}
