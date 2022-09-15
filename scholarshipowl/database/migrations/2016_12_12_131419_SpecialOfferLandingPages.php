<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Entity\SpecialOfferPage;

class SpecialOfferLandingPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::dropIfExists('special_offer_page');
        \Schema::create('special_offer_page', function(Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('package_id');
            $table->foreign('package_id')->references('package_id')->on('package');

            $table->string('url');
            $table->unique('url');

            $table->string('title');
            $table->string('icon_title1');
            $table->string('icon_title2');
            $table->string('icon_title3');
            $table->string('description');
            $table->string('scroll_to_text')->nullable();

            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_author')->nullable();

            $table->timestamps();
        });

        $specialOfferPage = new SpecialOfferPage();
        $specialOfferPage->setUrl('special-time-limited-membership-offer');
        $specialOfferPage->setPackage(\EntityManager::find(\App\Entity\Package::class, setting('payment.conversion.page.package') ?: 1));
        $specialOfferPage->setTitle('JOIN THE FASTEST GROWING SCHOLARSHIP APPLICATION ENGINE TODAY!');
        $specialOfferPage->setIconTitle1('AUTOMATIC APPLICATION TO SCHOLARSHIPS');
        $specialOfferPage->setIconTitle2('NEW SCHOLARSHIP OPPORTUNITIES EVERY MONTH!');
        $specialOfferPage->setIconTitle3('TRAINED PERSONAL ACCOUNT MANAGER TO HELP YOU FIND AND APPLY TO THE RIGHT MATCHES');
        $specialOfferPage->setDescription(setting('payment.conversion.page.text') ?: 'Content');

        \EntityManager::persist($specialOfferPage);
        \EntityManager::flush();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('special_offer_page');
    }
}
