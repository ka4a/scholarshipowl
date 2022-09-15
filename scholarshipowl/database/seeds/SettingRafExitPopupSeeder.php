<?php
 /**
 * Author: Ivan Krkotic (ivan@siriomedia.com)
 * Date: 03/9/2015
 */

class SettingRafExitPopupSeeder extends Seeder {
    public function run() {
        DB::table('setting')->insert(array(
            "name" => "missions.raf_exit_pop_active",
            "title" => "RAF Exit Pop - Active",
            "value" => "no",
            "type" => "select",
            "group" => "RAF Exit Pop",
            "options" => '{"yes":"Yes","no":"No"}',
        ));

        DB::table('setting')->insert(array(
            "name" => "missions.raf_exit_pop_text",
            "title" => "RAF Exit Pop - Popup Text",
            "value" => "",
            "type" => "text",
            "group" => "RAF Exit Pop"
        ));
    }
} 