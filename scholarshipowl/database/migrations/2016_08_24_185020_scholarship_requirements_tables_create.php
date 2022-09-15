<?php

use App\Entity\RequirementName;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ScholarshipRequirementsTablesCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();

        Schema::create('requirement_name', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->tinyInteger('type');
        });

        /**
         * Text: Transcript , Resume , Essay , Recommendation Letter ,CV, Cover Letter, Bio
        *  File:  Video ,  Class schedule , Proof of acceptance , Proof of enrollment
         * Img:  ProfilePic , Generic Picture
         */
        DB::table('requirement_name')->insert([
            ['type' => RequirementName::TYPE_TEXT, 'name' => 'Essay'],
            ['type' => RequirementName::TYPE_TEXT, 'name' => 'Transcript'],
            ['type' => RequirementName::TYPE_TEXT, 'name' => 'Resume'],
            ['type' => RequirementName::TYPE_TEXT, 'name' => 'Recommendation Letter'],
            ['type' => RequirementName::TYPE_TEXT, 'name' => 'CV'],
            ['type' => RequirementName::TYPE_TEXT, 'name' => 'Cover Letter'],
            ['type' => RequirementName::TYPE_TEXT, 'name' => 'Bio'],
            ['type' => RequirementName::TYPE_FILE, 'name' => 'Video'],
            ['type' => RequirementName::TYPE_FILE, 'name' => 'Class schedule'],
            ['type' => RequirementName::TYPE_FILE, 'name' => 'Proof of acceptance'],
            ['type' => RequirementName::TYPE_FILE, 'name' => 'Proof of enrollment'],
            ['type' => RequirementName::TYPE_IMAGE, 'name' => 'ProfilePic'],
            ['type' => RequirementName::TYPE_IMAGE, 'name' => 'Generic Picture'],
        ]);

        Schema::create('requirement_file', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('scholarship_id');
            $table->unsignedInteger('requirement_name_id');

            $table->string('title');
            $table->text('description');
            $table->string('file_extension')->nullable();
            $table->unsignedInteger('max_file_size')->nullable();

            $table->timestamps();

            $table->foreign('scholarship_id')
                ->references('scholarship_id')
                ->on('scholarship');
            $table->foreign('requirement_name_id')
                ->references('id')
                ->on('requirement_name');
        });

        Schema::create('requirement_image', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('scholarship_id');
            $table->unsignedInteger('requirement_name_id');

            $table->string('title');
            $table->text('description');
            $table->string('file_extension')->nullable();
            $table->unsignedInteger('max_file_size')->nullable();
            $table->unsignedInteger('min_width')->nullable();
            $table->unsignedInteger('max_width')->nullable();
            $table->unsignedInteger('min_height')->nullable();
            $table->unsignedInteger('max_height')->nullable();

            $table->timestamps();

            $table->foreign('scholarship_id')
                ->references('scholarship_id')
                ->on('scholarship');
            $table->foreign('requirement_name_id')
                ->references('id')
                ->on('requirement_name');
        });

        Schema::create('requirement_text', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('scholarship_id');
            $table->unsignedInteger('requirement_name_id');

            $table->string('title');
            $table->text('description');
            $table->string('send_type');
            $table->string('attachment_type')->nullable();
            $table->string('attachment_format')->nullable();
            $table->string('file_extension')->nullable();
            $table->tinyInteger('allow_file')->default(0);
            $table->unsignedInteger('max_file_size')->nullable();
            $table->unsignedInteger('min_words')->nullable();
            $table->unsignedInteger('max_words')->nullable();
            $table->unsignedInteger('min_characters')->nullable();
            $table->unsignedInteger('max_characters')->nullable();

            $table->timestamps();

            $table->foreign('scholarship_id')
                ->references('scholarship_id')
                ->on('scholarship');
            $table->foreign('requirement_name_id')
                ->references('id')
                ->on('requirement_name');
        });

        Schema::create('application_text', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('scholarship_id');
            $table->unsignedInteger('requirement_text_id');
            $table->unsignedInteger('account_file_id')->nullable();
            $table->text('text')->nullable();
            $table->timestamps();

            $table->foreign('account_id')
                ->references('account_id')
                ->on('account');
            $table->foreign('scholarship_id')
                ->references('scholarship_id')
                ->on('scholarship');
            $table->foreign('requirement_text_id')
                ->references('id')
                ->on('requirement_text');
            $table->foreign('account_file_id')
                ->references('id')
                ->on('account_file');

            $table->index(['account_id', 'scholarship_id']);
            $table->unique(['account_id', 'requirement_text_id'], 'unique_account_requirement_text');
        });

        Schema::create('application_file', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('scholarship_id');
            $table->unsignedInteger('requirement_file_id');
            $table->unsignedInteger('account_file_id');
            $table->timestamps();

            $table->foreign('account_id')
                ->references('account_id')
                ->on('account');
            $table->foreign('scholarship_id')
                ->references('scholarship_id')
                ->on('scholarship');
            $table->foreign('requirement_file_id')
                ->references('id')
                ->on('requirement_file');
            $table->foreign('account_file_id')
                ->references('id')
                ->on('account_file');

            $table->index(['account_id', 'scholarship_id']);
            $table->unique(['account_id', 'requirement_file_id'], 'unique_account_requirement_file');
        });

        Schema::create('application_image', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('scholarship_id');
            $table->unsignedInteger('requirement_image_id');
            $table->unsignedInteger('account_file_id');
            $table->timestamps();

            $table->foreign('account_id')
                ->references('account_id')
                ->on('account');
            $table->foreign('scholarship_id')
                ->references('scholarship_id')
                ->on('scholarship');
            $table->foreign('requirement_image_id')
                ->references('id')
                ->on('requirement_image');
            $table->foreign('account_file_id')
                ->references('id')
                ->on('account_file');

            $table->index(['account_id', 'scholarship_id']);
            $table->unique(['account_id', 'requirement_image_id'], 'unique_account_requirement_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_text');
        Schema::dropIfExists('application_file');
        Schema::dropIfExists('application_image');
        Schema::dropIfExists('requirement_file');
        Schema::dropIfExists('requirement_image');
        Schema::dropIfExists('requirement_text');
        Schema::dropIfExists('requirement_name');
    }
}
