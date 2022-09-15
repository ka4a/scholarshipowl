<?php

class AffiliateSeeder extends Seeder {
	public function run() {
		DB::statement("DELETE FROM affiliate_goal_response_data");
		DB::statement("DELETE FROM affiliate_goal_response");
		DB::statement("DELETE FROM affiliate_goal");
		DB::statement("DELETE FROM affiliate");
		
		
		// First 3 Affiliates
		
		DB::table("affiliate")->insert(array(
			"affiliate_id" => 1,
			"name" => "Deal Find Survey",
			"api_key" => "8jAyXyC2",
			"email" => "",
			"phone" => "",
			"website" => "http://www.dealfindersurvey.com",
			"description" => "Survey",
			"is_active" => 1,
		));
		
		DB::table("affiliate")->insert(array(
			"affiliate_id" => 2,
			"name" => "Cool Savings",
			"api_key" => "jpCGWFGs",
			"email" => "",
			"phone" => "",
			"website" => "http://coolsavings.com",
			"description" => "Discounts & coupons",
			"is_active" => 1,
		));
		
		DB::table("affiliate")->insert(array(
			"affiliate_id" => 3,
			"name" => "Quote Rocket",
			"api_key" => "PnKXAf2X",
			"email" => "",
			"phone" => "",
			"website" => "http://quoterocket.com",
			"description" => "Deals",
			"is_active" => 1,
		));
		
		
		// First 3 Affiliates Default Goals
		
		DB::table("affiliate_goal")->insert(array(
			"affiliate_id" => 1,
			"name" => "Default Goal",
			"url" => "http://www.srv2trking.com/click.track?CID=258629&AFID=356997&ADID=1029713&SID=&AffiliateReferenceID={account_id}",
		));
		
		DB::table("affiliate_goal")->insert(array(
			"affiliate_id" => 2,
			"name" => "Default Goal",
			"url" => "http://srv2trking.com/click.track?CID=299688&AFID=356997&ADID=1323965&SID=&AffiliateReferenceID={account_id}",
		));
		
		DB::table("affiliate_goal")->insert(array(
			"affiliate_id" => 3,
			"name" => "Default Goal",
			"url" => "http://www.sq2trk2.com/click.track?CID=287747&AFID=356997&ADID=1208759&SID=&AffiliateReferenceID={account_id}",
		));
	}
}

