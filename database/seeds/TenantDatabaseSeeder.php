<?php

use ClassyPOS\Models\Users\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addRoleAndPermissions();
        $this->call(ProductAdjustmentCategoriesTableSeeder::class);
    }

    private function addRoleAndPermissions()
    {
        // create permissions for an admin
        $adminPermissions = collect(['create user', 'edit user', 'delete user'])->map(function ($name) {
            return Permission::create(['name' => $name]);
        });

        // add admin role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo($adminPermissions);

        // add a default user role
        Role::create(['name' => 'user']);
    }
}
