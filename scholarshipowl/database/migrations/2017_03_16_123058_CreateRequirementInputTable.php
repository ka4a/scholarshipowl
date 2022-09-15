<?php

use App\Entity\RequirementName;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequirementInputTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('requirement_name')->insert([
            ['type' => RequirementName::TYPE_INPUT, 'name' => 'Video link'],
        ]);

        Schema::create('requirement_input', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('scholarship_id');
            $table->unsignedInteger('requirement_name_id');

            $table->string('title');
            $table->text('description');

            $table->timestamps();

            $table->foreign('scholarship_id')
                ->references('scholarship_id')
                ->on('scholarship');
            $table->foreign('requirement_name_id')
                ->references('id')
                ->on('requirement_name');
        });

        Schema::create('application_input', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('scholarship_id');
            $table->unsignedInteger('requirement_input_id');
            $table->text('text')->nullable();
            $table->timestamps();

            $table->foreign('account_id')
                ->references('account_id')
                ->on('account');
            $table->foreign('scholarship_id')
                ->references('scholarship_id')
                ->on('scholarship');
            $table->foreign('requirement_input_id')
                ->references('id')
                ->on('requirement_input');

            $table->index(['account_id', 'scholarship_id']);
            $table->unique(['account_id', 'requirement_input_id'], 'unique_account_requirement_input');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_input');
        Schema::dropIfExists('requirement_input');
    }
}
