<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFeatureCompanyDetailsSetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('feature_company_details_set', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('company_name');
            $table->string('company_name_2');
            $table->text('address_1');
            $table->text('address_2');
        });

        \DB::table('feature_company_details_set')->insert([
            'name' 	=> 'Default',
            'company_name' 	=> 'Owl Marketing Limited',
            'company_name_2' => 'Scholarship Application Services, LLC',
            'address_1' => '210/2 Manwel Dimech Street, SLM 1050, Sliema, Malta',
            'address_2' => '420 Veneto Irvine, CA 92614'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('feature_company_details_set');
    }
}
