<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ScholarshipRequirementSpecialEligibility extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requirement_special_eligibility', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('external_id')->nullable();
            $table->unsignedInteger('external_id_permanent')->nullable();
            $table->unsignedInteger('scholarship_id');
            $table->unsignedInteger('requirement_name_id');

            $table->string('title')->nullable();
            $table->char('permanent_tag', 20)->nullable(false);
            $table->text('description')->nullable();
            $table->text('text');


            $table->timestamps();

            $table->foreign('scholarship_id')
                ->references('scholarship_id')
                ->on('scholarship');
            $table->foreign('requirement_name_id')
                ->references('id')
                ->on('requirement_name');
        });

        Schema::create('application_special_eligibility', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('scholarship_id');
            $table->unsignedInteger('requirement_id');
            $table->boolean('val')->default(0);

            $table->timestamps();

            $table->foreign('account_id')
                ->references('account_id')
                ->on('account');
            $table->foreign('scholarship_id')
                ->references('scholarship_id')
                ->on('scholarship');
            $table->foreign('requirement_id', 'application_special_eligibility_req_id_foreign')
                ->references('id')
                ->on('requirement_special_eligibility');


            $table->index(['account_id', 'scholarship_id']);
            $table->unique(['account_id', 'requirement_id'], 'unique_account_req_sp_eligibility');
        });

        \DB::table("requirement_name")->insert([
            'name' => 'Special eligibility',
            'type' => 6,
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_special_eligibility');
        Schema::dropIfExists('requirement_special_eligibility');

        \DB::table('requirement_name')
            ->where(['name' => 'Special eligibility', 'type' => 6])
            ->delete();
    }
}
