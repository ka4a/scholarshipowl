<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FreemiumFsetAndPackage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table("feature_set")->insert([
            'name' => 'FreemiumMVP',
            'desktop_payment_set' => 1,
            'mobile_payment_set' => 1,
            'content_set' => 1,

        ]);

        \DB::table("package")->insert([
            'name' => 'FreemiumMVPPackage',
            'alias' => 'freemium-mvp',
            'is_freemium' => '1',
            'priority' => '99',
            'is_scholarships_unlimited' => '0',
            'freemium_recurrence_period' => 'never',
            'freemium_recurrence_value' => '0',
            'freemium_credits' => '1',
            'expiration_type' => \App\Entity\Package::EXPIRATION_TYPE_NO_EXPIRY
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('feature_set')
            ->where(['name' => 'FreemiumMVP'])
            ->delete();

        \DB::table('package')
            ->where(['alias' => 'freemium-mvp'])
            ->delete();
    }
}
