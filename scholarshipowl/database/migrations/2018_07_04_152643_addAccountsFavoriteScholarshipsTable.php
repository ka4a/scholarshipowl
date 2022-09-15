<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountsFavoriteScholarshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('accounts_favorite_scholarships', function(Blueprint $table) {
            $table->integer('account_id');
            $table->integer('scholarship_id');
            $table->integer('favorite');
            $table->integer('status');
            $table->primary(['account_id', 'scholarship_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('accounts_favorite_scholarships');
    }
}
