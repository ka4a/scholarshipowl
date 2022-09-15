<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Entity\Eligibility;
use App\Entity\Scholarship;
use App\Entity\Country;
use App\Entity\Field;

class EligibilityCountryDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $scholarships = \EntityManager::getRepository(Scholarship::class)->findAll();

        /** @var Scholarship $scholarship */
        foreach ($scholarships as $scholarship) {
            $scholarship->addEligibility(new Eligibility(Field::COUNTRY, Eligibility::TYPE_VALUE, Country::USA));
        }

        \EntityManager::flush();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
