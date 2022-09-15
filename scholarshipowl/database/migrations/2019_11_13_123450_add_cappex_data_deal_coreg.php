<?php

use App\Entity\Marketing\Submission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCappexDataDealCoreg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::table("coreg_plugins")->insert(array(
            "name" => Submission::NAME_CAPPEXDATADEAL,
            "is_visible" => false,
            "text" => "",
            "display_position" => "",
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table("coreg_plugins")->where("name", Submission::NAME_CAPPEXDATADEAL)->delete();
    }
}
