<?php



//Headline: Congratulations FNAME! You can get applied to [ElCount] scholarships right now
//Dollar icon: Automatically get applied to a total of $[SCAmount] available scholarship money from your [ElCount] scholarhip matches this month alone.
//Clock icon: ScholarshipOwl saves you time applying for scholarships so you can focus on more important things
//Loudspeaker icon: Get new Scholarship opportunity alerts first and get applied without hassle
//Arrow up icon: Increase your chances to get a scholarship with each application submitted!
//Button text: Get More Scholarships
//


class SettingPaymentPopupSeeder extends Seeder {
	public function run() {
		DB::table('setting')->insert(array(
			"name" => "paymentpopup.visible",
			"title" => "Payment Popup Visible",
			"value" => "\"yes\"",
			"type" => "select",
			"group" => "Payment Popup",
			"options" => '{"yes":"Yes","no":"No"}',
		));



		DB::table('setting')->insert(array(
			"name" => "paymentpopup.headtextup",
			"title" => "Head Text Up",
			"value" => "\"Congratulations [name]!\"",
			"type" => "string",
			"group" => "Payment Popup",
			"options" => "",
		));

		DB::table('setting')->insert(array(
			"name" => "paymentpopup.headtextdown",
			"title" => "Head Text Down",
			"value" => "\"You can get applied to [scholarships] scholarships right now\"",
			"type" => "string",
			"group" => "Payment Popup",
			"options" => "",
		));


		DB::table('setting')->insert(array(
			"name" => "paymentpopup.leftuptext",
			"title" => "Left Up Text",
			"value" => "\"Automatically get applied to a total of $[price] available scholarship money from your [scholarships] scholarhip matches this month alone.\"",
			"type" => "string",
			"group" => "Payment Popup",
			"options" => "",
		));

		DB::table('setting')->insert(array(
			"name" => "paymentpopup.leftdowntext",
			"title" => "Left Down Text",
			"value" => "\"Get new Scholarship opportunity alerts first and get applied without hassle\"",
			"type" => "string",
			"group" => "Payment Popup",
			"options" => "",
		));


		DB::table('setting')->insert(array(
			"name" => "paymentpopup.rightuptext",
			"title" => "Right Up Text",
			"value" => "\"ScholarshipOwl saves you time applying for scholarships so you can focus on more important things\"",
			"type" => "string",
			"group" => "Payment Popup",
			"options" => "",
		));

		DB::table('setting')->insert(array(
			"name" => "paymentpopup.rightdowntext",
			"title" => "Right Down Text",
			"value" => "\"Increase your chances to get a scholarship with each application submitted!\"",
			"type" => "string",
			"group" => "Payment Popup",
			"options" => "",
		));


		DB::table('setting')->insert(array(
			"name" => "paymentpopup.button",
			"title" => "Get More Scholarships",
			"value" => "\"Get More Scholarships\"",
			"type" => "string",
			"group" => "Payment Popup",
			"options" => "",
		));


		
	}
}
