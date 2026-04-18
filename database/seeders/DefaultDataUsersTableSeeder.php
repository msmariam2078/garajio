<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DefaultDataUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Default All Permission
        $allPermission = [
            [
                'name' => 'manage user',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create user',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit user',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete user',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage role',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create role',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit role',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete role',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage contact',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create contact',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit contact',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete contact',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage note',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create note',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit note',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete note',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage logged history',
                'guard_name' => 'web',

            ],
            [
                'name' => 'delete logged history',
                'guard_name' => 'web',

            ],
            [
                'name' => 'manage pricing packages',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create pricing packages',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit pricing packages',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete pricing packages',
                'guard_name' => 'web',
            ],

            [
                'name' => 'buy pricing packages',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage pricing transation',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage coupon',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create coupon',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit coupon',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete coupon',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage coupon history',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete coupon history',
                'guard_name' => 'web',
            ],

            [
                'name' => 'manage account settings',
                'guard_name' => 'web',

            ],
            [
                'name' => 'manage password settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage general settings',
                'guard_name' => 'web',

            ],
            [
                'name' => 'manage company settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage email settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage payment settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage seo settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage google recaptcha settings',
                'guard_name' => 'web',
            ],

            [
                'name' => 'manage client',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create client',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit client',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete client',
                'guard_name' => 'web',
            ],
            [
                'name' => 'show client',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage service & part',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create service & part',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit service & part',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete service & part',
                'guard_name' => 'web',
            ],
            [
                'name' => 'show service & part',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage asset',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create asset',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit asset',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete asset',
                'guard_name' => 'web',
            ],
            [
                'name' => 'show asset',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage wo request',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create wo request',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit wo request',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete wo request',
                'guard_name' => 'web',
            ],
            [
                'name' => 'show wo request',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage estimation',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create estimation',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit estimation',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete estimation',
                'guard_name' => 'web',
            ],
            [
                'name' => 'show estimation',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete estimation service & part',
                'guard_name' => 'web',
            ],
            [
                'name' => 'estimation status change',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage wo type',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create wo type',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit wo type',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete wo type',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage work order',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create work order',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit work order',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete work order',
                'guard_name' => 'web',
            ],
            [
                'name' => 'show work order',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete workorder service & part',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create workorder service task',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit workorder service task',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete workorder service task',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage service appointment',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create service appointment',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit service appointment',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete service appointment',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage invoice',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create invoice',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit invoice',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete invoice',
                'guard_name' => 'web',
            ],
            [
                'name' => 'show invoice',
                'guard_name' => 'web',
            ],

            [
                'name' => 'manage inquiry',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create inquiry',
                'guard_name' => 'web',
            ],
            [
                'name' => 'show inquiry',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit inquiry',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete inquiry',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage vehicle',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create vehicle',
                'guard_name' => 'web',
            ],
            [
                'name' => 'show vehicle',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit vehicle',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete vehicle',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage technician',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create technician',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit technician',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete technician',
                'guard_name' => 'web',
            ],
            [
                'name' => 'show technician',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage warehouse',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create warehouse',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit warehouse',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete warehouse',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage whatsapp chat',
                'guard_name' => 'web',
            ]

        ];
        Permission::insert($allPermission);

        // Default Super Admin Role
        $superAdminRoleData =  [
            'name' => 'super admin',
            'parent_id' => 0,
        ];
        $systemSuperAdminRole = Role::create($superAdminRoleData);
        $systemSuperAdminPermission = [
            ['name' => 'manage user'],
            ['name' => 'create user'],
            ['name' => 'edit user'],
            ['name' => 'delete user'],
            ['name' => 'manage contact'],
            ['name' => 'create contact'],
            ['name' => 'edit contact'],
            ['name' => 'delete contact'],
            ['name' => 'manage note'],
            ['name' => 'create note'],
            ['name' => 'edit note'],
            ['name' => 'delete note'],
            ['name' => 'manage pricing packages'],
            ['name' => 'create pricing packages'],
            ['name' => 'edit pricing packages'],
            ['name' => 'delete pricing packages'],
            ['name' => 'manage pricing transation'],
            ['name' => 'manage coupon'],
            ['name' => 'create coupon'],
            ['name' => 'edit coupon'],
            ['name' => 'delete coupon'],
            ['name' => 'manage coupon history'],
            ['name' => 'delete coupon history'],
            ['name' => 'manage account settings'],
            ['name' => 'manage password settings'],
            ['name' => 'manage general settings'],
            ['name' => 'manage email settings'],
            ['name' => 'manage payment settings'],
            ['name' => 'manage seo settings'],
            ['name' => 'manage google recaptcha settings'],
            ['name' => 'manage inquiry'],
            ['name' => 'manage vehicle'],
            ['name' => 'manage technician']

        ];
        $systemSuperAdminRole->givePermissionTo($systemSuperAdminPermission);
        // Default Super Admin
        $superAdminData =     [
            'first_name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('123456'),
            'type' => 'super admin',
            'lang' => 'english',
            'profile' => 'avatar.png',
        ];
        $systemSuperAdmin = User::create($superAdminData);
        $systemSuperAdmin->assignRole($systemSuperAdminRole);

        // Default Owner Role
        $ownerRoleData = [
            'name' => 'owner',
            'parent_id' => $systemSuperAdmin->id,
        ];
        $systemOwnerRole = Role::create($ownerRoleData);

        // Default Owner All Permissions
        $systemOwnerPermission = [
            ['name' => 'manage user'],
            ['name' => 'create user'],
            ['name' => 'edit user'],
            ['name' => 'delete user'],
            ['name' => 'manage role'],
            ['name' => 'create role'],
            ['name' => 'edit role'],
            ['name' => 'delete role'],
            ['name' => 'manage contact'],
            ['name' => 'create contact'],
            ['name' => 'edit contact'],
            ['name' => 'delete contact'],
            ['name' => 'manage note'],
            ['name' => 'create note'],
            ['name' => 'edit note'],
            ['name' => 'delete note'],
            ['name' => 'manage logged history'],
            ['name' => 'delete logged history'],
            ['name' => 'manage pricing packages'],
            ['name' => 'buy pricing packages'],
            ['name' => 'manage pricing transation'],
            ['name' => 'manage account settings'],
            ['name' => 'manage password settings'],
            ['name' => 'manage general settings'],
            ['name' => 'manage company settings'],
            ['name' => 'manage email settings'],

            ['name' => 'manage client'],
            ['name' => 'create client'],
            ['name' => 'edit client'],
            ['name' => 'delete client'],
            ['name' => 'show client'],
            ['name' => 'manage service & part'],
            ['name' => 'create service & part'],
            ['name' => 'edit service & part'],
            ['name' => 'delete service & part'],
            ['name' => 'show service & part'],
            ['name' => 'manage asset'],
            ['name' => 'create asset'],
            ['name' => 'edit asset'],
            ['name' => 'delete asset'],
            ['name' => 'show asset'],
            ['name' => 'manage wo request'],
            ['name' => 'create wo request'],
            ['name' => 'edit wo request'],
            ['name' => 'delete wo request'],
            ['name' => 'show wo request'],
            ['name' => 'manage estimation'],
            ['name' => 'create estimation'],
            ['name' => 'edit estimation'],
            ['name' => 'delete estimation'],
            ['name' => 'show estimation'],
            ['name' => 'delete estimation service & part'],
            ['name' => 'estimation status change'],
            ['name' => 'manage work order'],
            ['name' => 'create work order'],
            ['name' => 'edit work order'],
            ['name' => 'delete work order'],
            ['name' => 'show work order'],
            ['name' => 'delete workorder service & part'],
            ['name' => 'create workorder service task'],
            ['name' => 'edit workorder service task'],
            ['name' => 'delete workorder service task'],
            ['name' => 'manage service appointment'],
            ['name' => 'create service appointment'],
            ['name' => 'edit service appointment'],
            ['name' => 'delete service appointment'],
            ['name' => 'manage invoice'],
            ['name' => 'create invoice'],
            ['name' => 'edit invoice'],
            ['name' => 'delete invoice'],
            ['name' => 'show invoice'],
            ['name' => 'manage wo type'],
            ['name' => 'create wo type'],
            ['name' => 'edit wo type'],
            ['name' => 'delete wo type'],

            ['name' => 'manage inquiry'],
            ['name' => 'create inquiry'],
            ['name' => 'edit inquiry'],
            ['name' => 'delete inquiry'],
            ['name' => 'show inquiry'],
            ['name' => 'manage vehicle'],
            ['name' => 'create vehicle'],
            ['name' => 'edit vehicle'],
            ['name' => 'delete vehicle'],
            ['name' => 'show vehicle'],
            ['name' => 'manage technician'],
            ['name' => 'create technician'],
            ['name' => 'edit technician'],
            ['name' => 'delete technician'],
            ['name' => 'show technician'],
            ['name' => 'manage warehouse'],
            ['name' => 'create warehouse'],
            ['name' => 'edit warehouse'],
            ['name' => 'delete warehouse'],
            ['name' => 'manage whatsapp chat']

        ];
        $systemOwnerRole->givePermissionTo($systemOwnerPermission);

        // Default Owner Create
        $ownerData =    [
            'first_name' => 'Owner',
            'email' => 'owner@gmail.com',
            'password' => Hash::make('123456'),
            'type' => 'owner',
            'lang' => 'english',
            'profile' => 'avatar.png',
            'subscription' => 1,
            'parent_id' => $systemSuperAdmin->id,
        ];
        $systemOwner = User::create($ownerData);
        // Default Owner Role Assign
        $systemOwner->assignRole($systemOwnerRole);


        // Default Manager Role
        $managerRoleData =  [
            'name' => 'manager',
            'parent_id' => $systemOwner->id,
        ];
        $systemManagerRole = Role::create($managerRoleData);
        // Default Manager All Permissions
        $systemManagerPermission = [
            ['name' => 'manage client'],
            ['name' => 'create client'],
            ['name' => 'edit client'],
            ['name' => 'delete client'],
            ['name' => 'show client'],
            ['name' => 'manage service & part'],
            ['name' => 'create service & part'],
            ['name' => 'edit service & part'],
            ['name' => 'delete service & part'],
            ['name' => 'show service & part'],
            ['name' => 'manage asset'],
            ['name' => 'create asset'],
            ['name' => 'edit asset'],
            ['name' => 'delete asset'],
            ['name' => 'show asset'],
            ['name' => 'manage wo request'],
            ['name' => 'create wo request'],
            ['name' => 'edit wo request'],
            ['name' => 'delete wo request'],
            ['name' => 'show wo request'],
            ['name' => 'manage estimation'],
            ['name' => 'create estimation'],
            ['name' => 'edit estimation'],
            ['name' => 'delete estimation'],
            ['name' => 'show estimation'],
            ['name' => 'delete estimation service & part'],
            ['name' => 'estimation status change'],
            ['name' => 'manage work order'],
            ['name' => 'create work order'],
            ['name' => 'edit work order'],
            ['name' => 'delete work order'],
            ['name' => 'show work order'],
            ['name' => 'delete workorder service & part'],
            ['name' => 'create workorder service task'],
            ['name' => 'edit workorder service task'],
            ['name' => 'delete workorder service task'],
            ['name' => 'manage service appointment'],
            ['name' => 'create service appointment'],
            ['name' => 'edit service appointment'],
            ['name' => 'delete service appointment'],
            ['name' => 'manage invoice'],
            ['name' => 'create invoice'],
            ['name' => 'edit invoice'],
            ['name' => 'delete invoice'],
            ['name' => 'show invoice'],
            ['name' => 'show invoice'],
        ];
        $systemManagerRole->givePermissionTo($systemManagerPermission);
        // Default Manager Create
        $managerData =   [
            'first_name' => 'Manager',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('123456'),
            'type' => 'manager',
            'lang' => 'english',
            'profile' => 'avatar.png',
            'subscription' => 0,
            'parent_id' => $systemOwner->id,
        ];
        $systemManager = User::create($managerData);
        // Default Manager Role Assign
        $systemManager->assignRole($systemManagerRole);


        // Default Client Role
        $systemManagerRole = defaultClientCreate($systemOwner->id);

        // Subscription default data
        $subscriptionData = [
            'title' => 'Basic',
            'package_amount' => 0,
            'interval' => 'Unlimited',
            'user_limit' => 10,
            'client_limit' => 10,
            'enabled_logged_history' => 1,
        ];
        \App\Models\Subscription::create($subscriptionData);
    }
}
