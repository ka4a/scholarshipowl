<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApplyMePushNotificationAdminPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("admin_role_permission")->insert([
            "admin_role_id" => 4,
            "permission"    => "route::ApplyMe"
        ]);

        DB::table("admin_role_permission")->insert([
            "admin_role_id" => 4,
            "permission"    => "route::apply-me"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete("DELETE FROM admin_role_permission WHERE permission = 'route::ApplyMe'  AND admin_role_id = 4");
        DB::delete("DELETE FROM admin_role_permission WHERE permission = 'route::apply-me'  AND admin_role_id = 4");
    }
}
