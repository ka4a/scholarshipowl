<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnfavoriteScholarshipsFromTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('accounts_favorite_scholarships', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        DB::delete("DELETE FROM accounts_favorite_scholarships WHERE favorite = 0;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('accounts_favorite_scholarships', function(Blueprint $table) {
            $table->integer('status')->nullable();
        });
    }
}
