<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(['email' => 'admin@local.test'], [
            'name' => 'Administrador',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'status' => 'active',
        ]);

        $role = Role::query()->firstOrCreate(['slug' => 'admin'], [
            'name' => 'Admin',
            'description' => 'Acesso total ao sistema.',
        ]);

        $admin->roles()->syncWithoutDetaching([$role->id]);
    }
}
