<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
        /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolesStructure = [
            'Super Admin' => [
                'dashboard' => 'r',
                'orders' => 'r,u',
                'recharges' => 'r,u',
                'plans' => 'r,c,u,d',
                'users' => 'r,c,u,d',
                'pricings' => 'r,c,u,d',
                'customers' => 'r,c,u,d',
                'senderids' => 'r,c,u,d',
                'campaigns' => 'r,c,u,d',
                'campaigns_sms' => 'r,u,d',

                'roles' => 'r,c,u,d',
                'permissions' => 'r,c',

                // settings
                'cron-jobs' => 'r',
                'notice' => 'r,u',
                'services' => 'r,c,u,d',
                'sms-settings' => 'r,u',
                'reports' => 'r,u',
                'faqs' => 'r,c,u,d',
                'testimonials' => 'r,c,u,d',
                'senderips' => 'r,u',
                'smsgateways' => 'r,c,u,d',
                'gateways-types' => 'r,c,u,d',
                'settings' => 'r,u',
                'gateways' => 'r,u',
                'notifications' => 'r,u',
                'currencies' => 'r,c,u,d',
                'blogs' => 'r,c,u,d',
                'newsletters' => 'r,d',
            ],

            'Admin' => [
                'dashboard' => 'r',
                'users' => 'r,c,u,d',
                'plans' => 'r,c,u,d',
                'customers' => 'r,c,u,d',
            ],
        ];

        foreach ($rolesStructure as $key => $modules) {
            // Create a new role
            $role = Role::firstOrCreate([
                'name' => str($key)->remove(' ')->lower(),
                'guard_name' => 'web'
            ]);
            $permissions = [];

            $this->command->info('Creating Role '. strtoupper($key));

            // Reading role permission modules
            foreach ($modules as $module => $value) {

                foreach (explode(',', $value) as $perm) {

                    $permissionValue = $this->permissionMap()->get($perm);

                    $permissions[] = Permission::firstOrCreate([
                        'name' => $module . '-' . $permissionValue,
                        'guard_name' => 'web'
                    ])->id;

                    $this->command->info('Creating Permission to '.$permissionValue.' for '. $module);
                }
            }

            // Attach all permissions to the role
            $role->permissions()->sync($permissions);

            $this->command->info("Creating '{$key}' user");
            // Create default user for each role
            $user = User::create([
                'phone' => '01111111',
                'email_verified_at' => now(),
                'name' => ucwords(str_replace('_', ' ', $key)),
                'password' => bcrypt(str($key)->remove(' ')->lower()),
                'email' => str($key)->remove(' ')->lower().'@'.str($key)->remove(' ')->lower().'.com',
                'role' => str($key)->remove(' ')->lower() == 'superadmin' ? 'superadmin' : 'admin',
            ]);

            $user->assignRole($role);
        }
    }

    private function permissionMap() {
        return collect([
            'c' => 'create',
            'r' => 'read',
            'u' => 'update',
            'd' => 'delete',
        ]);
    }
}
