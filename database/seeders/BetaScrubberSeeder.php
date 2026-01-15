<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BetaScrubberSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear the Spatie Cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Wipe existing Role/Permission data (The "Scrub")
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        Role::truncate();
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 3. Create fresh Permissions
        $perms = ['change own', 'change all', 'change members', 'manage-rituals'];
        foreach ($perms as $p) {
            Permission::create(['name' => $p]);
        }

        // 4. Create the 5 Core Roles
        $admin   = Role::create(['name' => 'admin']);
        $druid   = Role::create(['name' => 'senior_druid']);
        $member  = Role::create(['name' => 'member']);
        $pending = Role::create(['name' => 'pending']);
        $joiner  = Role::create(['name' => 'joiner']);

        // 5. Map Permissions to Roles
        $admin->givePermissionTo(Permission::all());
        $druid->givePermissionTo(Permission::all());
        $member->givePermissionTo('change own');

        // 6. Assign Roles to you and the Senior Druid
        // Remember: Only active members have user records! [cite: 2025-12-31]
        $powerUsers = User::whereIn('email', [
            'michael.talvola@gmail.com',
            'lisa.saylorgentry@gmail.com'
        ])->get();

        foreach ($powerUsers as $user) {
            $user->assignRole(['admin', 'senior_druid']);
        }
    }
}
