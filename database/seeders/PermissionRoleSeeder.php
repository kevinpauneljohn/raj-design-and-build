<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        Role::create(['name' => 'super admin']);
//        Role::create(['name' => 'sales administrator']);
//        Role::create(['name' => 'finance administrator']);
//        Role::create(['name' => 'procurement manager']);
//        Role::create(['name' => 'panelist']);
//        Role::create(['name' => 'project manager']);
//
//        //user
//        Permission::create(['name' => 'view user']);
//        Permission::create(['name' => 'add user'])->assignRole(['sales administrator']);
//        Permission::create(['name' => 'edit user'])->assignRole(['sales administrator']);
//        Permission::create(['name' => 'delete user'])->assignRole(['sales administrator']);
//        //end user
//
//        //Roles
//        Permission::create(['name' => 'view role']);
//        Permission::create(['name' => 'add role']);
//        Permission::create(['name' => 'edit role']);
//        Permission::create(['name' => 'delete role']);
//        //end roles
//
//        //Permissions
//        Permission::create(['name' => 'view permission']);
//        Permission::create(['name' => 'add permission']);
//        Permission::create(['name' => 'edit permission']);
//        Permission::create(['name' => 'delete permission']);
//        //end permissions
//
//        //Clients
//        Permission::create(['name' => 'view client']);
//        Permission::create(['name' => 'add client']);
//        Permission::create(['name' => 'edit client']);
//        Permission::create(['name' => 'delete client']);
//        //end permissions

//        //Supplier
//        Permission::create(['name' => 'view supplier'])->assignRole(['sales administrator','procurement manager']);
//        Permission::create(['name' => 'add supplier'])->assignRole(['sales administrator','procurement manager']);
//        Permission::create(['name' => 'edit supplier'])->assignRole(['sales administrator','procurement manager']);
//        Permission::create(['name' => 'delete supplier'])->assignRole(['sales administrator','procurement manager']);
//        Permission::create(['name' => 'manage supplier'])->assignRole(['sales administrator','procurement manager']);
//        //end Supplier

        //item
//        Permission::create(['name' => 'view item'])->assignRole(['sales administrator','procurement manager']);
//        Permission::create(['name' => 'add item'])->assignRole(['sales administrator','procurement manager']);
//        Permission::create(['name' => 'edit item'])->assignRole(['sales administrator','procurement manager']);
//        Permission::create(['name' => 'delete item'])->assignRole(['sales administrator','procurement manager']);
//        Permission::create(['name' => 'upload item'])->assignRole(['sales administrator','procurement manager']);
//        Permission::create(['name' => 'download item template'])->assignRole(['sales administrator','procurement manager']);
        //end item

        //applicant
//        Permission::create(['name' => 'view applicant'])->assignRole(['panelist']);
//        Permission::create(['name' => 'add applicant'])->assignRole(['panelist']);
//        Permission::create(['name' => 'edit applicant'])->assignRole(['panelist']);
//        Permission::create(['name' => 'delete applicant'])->assignRole(['panelist']);
//        Permission::create(['name' => 'score applicant'])->assignRole(['panelist']);
//        //end Applicant

//        //criteria
//        Permission::create(['name' => 'view criteria'])->assignRole(['panelist']);
//        Permission::create(['name' => 'add criteria'])->assignRole(['panelist']);
//        Permission::create(['name' => 'edit criteria'])->assignRole(['panelist']);
//        Permission::create(['name' => 'delete criteria'])->assignRole(['panelist']);
////        //end criteria

        //kpi
//        Permission::create(['name' => 'view kpi']);
//        Permission::create(['name' => 'add kpi']);
//        Permission::create(['name' => 'edit kpi']);
//        Permission::create(['name' => 'delete kpi']);
//        //end kpi

//        //project
//        Permission::create(['name' => 'view project']);
//        Permission::create(['name' => 'add project']);
//        Permission::create(['name' => 'edit project']);
//        Permission::create(['name' => 'delete project']);
///       //end project

        //phase
        Permission::create(['name' => 'view phase']);
        Permission::create(['name' => 'add phase']);
        Permission::create(['name' => 'edit phase']);
        Permission::create(['name' => 'delete phase']);
        //end phase
    }
}
