<?php

use Illuminate\Database\Migrations\Migration;

class AddRolesAcl extends Migration
{
    public function up()
    {
        $roleInstructorHelp = \Spatie\Permission\Models\Role::create(['name' => 'INSTRUCTOR_HELP']);
        $roleInstructor = \Spatie\Permission\Models\Role::create(['name' => 'INSTRUCTOR']);
        $roleRTQ = \Spatie\Permission\Models\Role::create(['name' => 'RTQ']);
        $roleBuyer = \Spatie\Permission\Models\Role::create(['name' => 'BUYER']);
        $roleDemo = \Spatie\Permission\Models\Role::create(['name' => 'DEMO']);

        $permissions = [
            'start boat trip',
            'end boat trip',
            'delete boat trip',

            'add boat trip',
            'add reservation',

            'show fleets',
            'enable fleet',
            'disable fleet',
            'add fleet',
            'edit fleet',

            'show rental package',
            'add rental package',
            'edit rental package',

            'show sailor rental package',
            'add sailor rental package',
            'add hours sailor rental package',
            'sub hours sailor rental package',

            'show collaborator',
            'billings',
            'edit profile',
            'send contact mail'
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        $roleBuyer->givePermissionTo([
            'billings',
            'edit profile',
            'send contact mail'
        ]);

        $roleInstructorHelp->givePermissionTo([
            'start boat trip',
            'end boat trip',
            'edit profile',
            'send contact mail'
        ]);

        $roleInstructor->givePermissionTo([
            'start boat trip',
            'end boat trip',
            'delete boat trip',

            'add boat trip',
            'add reservation',

            'show sailor rental package',
            'add sailor rental package',
            'add hours sailor rental package',
            'sub hours sailor rental package',
            'edit profile',
            'send contact mail'
        ]);

        $roleRTQ->givePermissionTo([
            'start boat trip',
            'end boat trip',
            'delete boat trip',

            'add boat trip',
            'add reservation',

            'show fleets',
            'enable fleet',
            'disable fleet',
            'add fleet',
            'edit fleet',

            'show rental package',
            'add rental package',
            'edit rental package',

            'show sailor rental package',
            'add sailor rental package',
            'add hours sailor rental package',
            'sub hours sailor rental package',

            'show collaborator',
            'edit profile',
            'send contact mail'
        ]);

        $roleDemo->givePermissionTo([
            'start boat trip',
            'end boat trip',
            'delete boat trip',

            'add boat trip',
            'add reservation',

            'show fleets',
            'enable fleet',
            'disable fleet',
            'add fleet',
            'edit fleet',

            'show rental package',
            'add rental package',
            'edit rental package',

            'show sailor rental package',
            'add sailor rental package',
            'add hours sailor rental package',
            'sub hours sailor rental package',
        ]);
    }

    public function down()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        foreach($roles as $role){
            $role->delete();
        }

        $permissions = \Spatie\Permission\Models\Permission::all();
        foreach($permissions as $permission){
            $permission->delete();
        }
    }
}
