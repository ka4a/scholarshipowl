<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ScholarshipRequirementSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requirement_survey', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('external_id')->nullable();
            $table->unsignedInteger('external_id_permanent')->nullable();
            $table->unsignedInteger('scholarship_id');
            $table->unsignedInteger('requirement_name_id');

            $table->string('title')->nullable();
            $table->char('permanent_tag', 20)->nullable(false);
            $table->text('description')->nullable();
            $table->json('survey');


            $table->timestamps();

            $table->foreign('scholarship_id')
                ->references('scholarship_id')
                ->on('scholarship');
            $table->foreign('requirement_name_id')
                ->references('id')
                ->on('requirement_name');
        });

        Schema::create('application_survey', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('scholarship_id');
            $table->unsignedInteger('requirement_survey_id');

            $table->json('answers');
            $table->timestamps();

            $table->foreign('account_id')
                ->references('account_id')
                ->on('account');
            $table->foreign('scholarship_id')
                ->references('scholarship_id')
                ->on('scholarship');
            $table->foreign('requirement_survey_id')
                ->references('id')
                ->on('requirement_survey');


            $table->index(['account_id', 'scholarship_id']);
            $table->unique(['account_id', 'requirement_survey_id'], 'unique_account_requirement_survey');
        });

        \DB::table("requirement_name")->insert([
            'name' => 'Survey',
            'type' => 5,
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_survey');
        Schema::dropIfExists('requirement_survey');

        \DB::table('requirement_name')
            ->where(['name' => 'Survey', 'type' => 5])
            ->delete();
    }
}
