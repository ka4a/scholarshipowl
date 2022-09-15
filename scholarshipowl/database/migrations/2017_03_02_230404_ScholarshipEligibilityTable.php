<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ScholarshipEligibilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::dropIfExists('account_eligibility');
        \Schema::dropIfExists('scholarship_eligibility');

//        \DB::statement('RENAME TABLE eligibility TO eligibility_old;');
//        \Schema::dropIfExists('eligibility');

//        \Schema::create('eligibility', function(Blueprint $table) {
//            $table->unsignedInteger('id', true);
//
//            $table->unsignedInteger('field_id');
//            $table->tinyInteger('type');
//            $table->char('value');
//
//            $table->unique(['field_id', 'type', 'value']);
//        });

//        \Schema::table('eligibility', function(Blueprint $table) {
//            $table->foreign('field_id')->refenrences('field_id')->on('field');
//        });

//        \DB::statement(
//            'INSERT INTO eligibility (field_id, type, value)
//             SELECT DISTINCT e.field_id, e.type, e.value
//             FROM eligibility_old e'
//        );

//        \Schema::table('scholarship', function(Blueprint $table) {
//            $table->integer('scholarship_id', true, true)->change();
//        });

//        \Schema::create('scholarship_eligibility', function(Blueprint $table) {
//            $table->integer('scholarship_id', false, true);
//            $table->foreign('scholarship_id')->references('scholarship_id')->on('scholarship');
//            $table->index('scholarship_id');
//
//            $table->unsignedInteger('eligibility_id');
//            $table->foreign('eligibility_id', 'fk_scholarship_eligibility')->references('id')->on('eligibility');
//            $table->index('eligibility_id');
//
//            $table->primary(['scholarship_id', 'eligibility_id']);
//            $table->index(['eligibility_id', 'scholarship_id']);
//        });
//
//        foreach (\DB::table('eligibility_old')->get() as $eligibility) {
//            $row = \DB::table('eligibility')
//                ->where('field_id', '=', $eligibility->field_id)
//                ->where('type', '=', $eligibility->type)
//                ->where('value', '=', $eligibility->value)
//                ->get(['id']);
//
//            \DB::insert(
//                'INSERT IGNORE scholarship_eligibility (scholarship_id, eligibility_id) VALUES(?, ?)',
//                [$eligibility->scholarship_id, $row[0]->id]
//            );
//        };

        /*
        \Schema::create('account_eligibility', function(Blueprint $table) {
            $table->unsignedInteger('account_id');
            $table->foreign('account_id')->references('account_id')->on('account');
            $table->index('account_id');

            $table->unsignedInteger('eligibility_id');
            $table->foreign('eligibility_id')->references('id')->on('eligibility');
            $table->index('eligibility_id');

            $table->primary(['account_id', 'eligibility_id']);
            $table->index(['eligibility_id', 'account_id']);
        });

        \Schema::create('scholarship_eligibility', function(Blueprint $table) {
            $table->string('hash', 32);
            $table->index('hash');

            $table->unsignedInteger('eligibility_id');
            $table->foreign('eligibility_id', 'fk_scholarship_eligibility')->references('id')->on('eligibility');
            $table->index('eligibility_id');

            $table->primary(['eligibility_id', 'hash']);
            $table->index(['hash', 'eligibility_id']);
        });

        \Schema::table('scholarship', function(Blueprint $table) {
            $table->string('eligibility_hash', 32)->nullable();
        });

        $scholarships = [];

        foreach (\DB::table('eligibility_old')->get() as $eligibility) {
            $id = \DB::table('eligibility')
                ->where('field_id', '=', $eligibility->field_id)
                ->where('type', '=', $eligibility->type)
                ->where('value', '=', $eligibility->value)
                ->get(['id']);

            $scholarships[$eligibility->scholarship_id][] = $id[0]->id ?? null;
        }

        foreach ($scholarships as $scholarship => $eligibilities) {
            $eligibilities = array_unique($eligibilities);
            sort($eligibilities);
            $hash = md5(implode(',', $eligibilities));
            \DB::insert(sprintf(
                'INSERT IGNORE scholarship_eligibility (hash, eligibility_id) VALUES %s',
                implode(',', array_map(
                    function($id) use ($hash) {
                        return sprintf('("%s", %d)', $hash, $id);
                    },
                    $eligibilities
                ))
            ));

            \DB::update('UPDATE scholarship SET eligibility_hash = ? WHERE scholarship_id = ?', [$hash, $scholarship]);
        }
        */

        \Schema::table('account', function(Blueprint $table) {
            $table->string('eligibility_id', 32)->nullable()->before('eligibility_update');
        });

        \Schema::create('account_eligibility', function(Blueprint $table) {
            $table->string('id', 32);
            $table->primary('id');
            $table->text('list');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('account_eligibility');

        \Schema::table('account', function(Blueprint $table) {
            $table->dropColumn('eligibility_id');
        });

//        \Schema::table('scholarship', function(Blueprint $table) {
//            $table->dropColumn('eligibility_hash');
//        });

        \Schema::dropIfExists('scholarship_eligibility');

//        \Schema::dropIfExists('eligibility');
//        \DB::statement('RENAME TABLE `eligibility_old` TO `eligibility`;');
    }
}
