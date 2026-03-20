<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = ['users', 'roles', 'clients', 'products', 'proposals', 'proposal_templates', 'dashboard', 'audit_logs', 'files', 'imports'];
        $actions = ['view', 'create', 'update', 'delete', 'export', 'approve'];

        $permissionIds = collect($modules)
            ->flatMap(fn (string $module) => collect($actions)->map(fn (string $action) => Permission::query()->firstOrCreate([
                'slug' => sprintf('%s.%s', $module, $action),
            ], [
                'module' => $module,
                'action' => $action,
                'name' => strtoupper($module . '.' . $action),
            ])->id))
            ->all();

        $roles = [
            'admin' => 'Acesso total ao sistema.',
            'gestor' => 'Acompanha indicadores e aprova propostas.',
            'comercial' => 'Gerencia clientes, produtos e propostas.',
            'operacional' => 'Opera cadastros e apoio operacional.',
        ];

        foreach ($roles as $slug => $description) {
            $role = Role::query()->firstOrCreate(['slug' => $slug], [
                'name' => ucfirst($slug),
                'description' => $description,
            ]);

            if ($slug === 'admin') {
                $role->permissions()->sync($permissionIds);
            }
        }
    }
}
