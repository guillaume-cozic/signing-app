<?php

use Illuminate\Database\Migrations\Migration;

class AddRoles extends Migration
{
    public function up()
    {
        \Spatie\Permission\Models\Role::create(['name' => 'SIGNING']);
        \Spatie\Permission\Models\Role::create(['name' => 'BUYER']);
        \Spatie\Permission\Models\Role::create(['name' => 'MEMBER']);
    }

    public function down()
    {
        //
    }
}
