<?php

class SettingMissionsAccountLinkSeeder extends Seeder {
	public function run() {
		DB::table('setting')->insert(array(
			"name" => "missions.tab_link_visible",
			"title" => "Mission Tab - Link Visible",
			"value" => "no",
			"type" => "select",
			"group" => "Missions",
			"options" => '{"yes":"Yes","no":"No"}',
		));
		
        DB::table('setting')->insert(array(
            "name" => "missions.tab_link_text",
            "title" => "Mission Tab - Link Text",
            "value" => "",
            "type" => "text",
            "group" => "Missions"
        ));
        
        DB::table('setting')->insert(array(
        	"name" => "missions.tab_mission_id",
        	"title" => "Mission Tab - Mission ID",
        	"value" => "",
        	"type" => "int",
        	"group" => "Missions"
        ));
	}
}
