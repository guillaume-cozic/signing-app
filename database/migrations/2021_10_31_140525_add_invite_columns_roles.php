<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInviteColumnsRoles extends Migration
{
    public function up()
    {
        Schema::table(\Config::get('teamwork.team_invites_table'), function (Blueprint $table) {
            $table->json('roles')->nullable();
        });
    }

    public function down()
    {
        Schema::table(\Config::get('teamwork.team_invites_table'), function (Blueprint $table) {
            $table->dropColumn('roles');
        });
    }
}
