<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CmsAddRegisterPaymentPage extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table("cms")->insert(array(
				"url" => "/register-payment",
				"page" => "Payment",
				"title" => "ScholarshipOwl - hundreds of scholarships one click away",
				"keywords" => "students, education, scholarship consultants, apply for scholarship, graduate debt free, financial aid, account managers",
				"description" => "Scholarship Owl is a collection of dedicated professionals looking to make finding money easier for students.",
				"author" => " ",
			)
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table("cms")->where("url", "/register-payment")->delete();
	}

}
