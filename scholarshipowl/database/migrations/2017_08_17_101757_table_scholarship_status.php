<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Entity\ScholarshipStatus;

class TableScholarshipStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::dropIfExists('scholarship_status');
        \Schema::create('scholarship_status', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unique('name');
        });

        \DB::table('scholarship_status')->insert([
            ['id' => ScholarshipStatus::PUBLISHED, 'name' => 'Published'],
            ['id' => ScholarshipStatus::UNPUBLISHED, 'name' => 'Unpublished'],
            ['id' => ScholarshipStatus::EXPIRED, 'name' => 'Expired'],
        ]);

        \Schema::table('scholarship', function(Blueprint $table) {
            $table->unsignedInteger('status')->default(ScholarshipStatus::UNPUBLISHED)->before('is_active');
        });

        \DB::statement(sprintf('UPDATE scholarship SET status = %s WHERE is_active = TRUE', ScholarshipStatus::PUBLISHED));
        \DB::statement(sprintf('UPDATE scholarship SET status = %s WHERE is_active = FALSE', ScholarshipStatus::UNPUBLISHED));

        \Schema::table('scholarship', function(Blueprint $table) {
            $table->foreign('status', 'fk_scholarship_status_id')->references('id')->on('scholarship_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('scholarship', function(Blueprint $table) {
            $table->dropForeign('fk_scholarship_status_id');
            $table->dropColumn('status');
        });

        \Schema::dropIfExists('scholarship_status');
    }
}
