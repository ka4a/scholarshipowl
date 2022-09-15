<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFileCategoryList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::update('UPDATE account_file_categories SET name = "Essay" WHERE id = 2');
        DB::update('UPDATE account_file_categories SET name = "Transcript" WHERE id = 3');
        DB::update('UPDATE account_file_categories SET name = "Resume" WHERE id = 4');
        DB::update('UPDATE account_file_categories SET name = "Recommendation Letter" WHERE id = 5');
        DB::update('UPDATE account_file_categories SET name = "CV" WHERE id = 6');

        DB::insert("INSERT INTO account_file_categories (name) VALUES ('Cover Letter')");
        DB::insert("INSERT INTO account_file_categories (name) VALUES ('Bio')");
        DB::insert("INSERT INTO account_file_categories (name) VALUES ('Video')");
        DB::insert("INSERT INTO account_file_categories (name) VALUES ('Class schedule')");
        DB::insert("INSERT INTO account_file_categories (name) VALUES ('Proof of acceptance')");
        DB::insert("INSERT INTO account_file_categories (name) VALUES ('Proof of enrollment')");
        DB::insert("INSERT INTO account_file_categories (name) VALUES ('ProfilePic')");
        DB::insert("INSERT INTO account_file_categories (name) VALUES ('Generic Picture')");
        DB::insert("INSERT INTO account_file_categories (name) VALUES ('Video link')");
        DB::insert("INSERT INTO account_file_categories (name) VALUES ('Link')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::update("INSERT INTO account_file_categories (id, name) VALUES (1, 'Other')");
        DB::update("INSERT INTO account_file_categories (id, name) VALUES (2, 'Essay')");
        DB::update("INSERT INTO account_file_categories (id, name) VALUES (3, 'Grade transcript')");
        DB::update("INSERT INTO account_file_categories (id, name) VALUES (4, 'Bio')");
        DB::update("INSERT INTO account_file_categories (id, name) VALUES (5, 'CV')");
        DB::update("INSERT INTO account_file_categories (id, name) VALUES (6, 'Profile picture')");
    }
}
