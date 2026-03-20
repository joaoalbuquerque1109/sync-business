<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Product;
use App\Models\ProposalTemplate;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::query()->where('email', 'admin@local.test')->value('id');

        Client::query()->updateOrCreate(
            ['company_name' => 'Hospital Santa Aurora'],
            [
                'trade_name' => 'Santa Aurora',
                'email' => 'compras@santaaurora.test',
                'phone' => '(85) 3000-1000',
                'city' => 'Fortaleza',
                'state' => 'CE',
                'source' => 'seed',
                'is_active' => true,
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
        );

        Client::query()->updateOrCreate(
            ['company_name' => 'Clinica Vida Integral'],
            [
                'trade_name' => 'Vida Integral',
                'email' => 'contato@vidaintegral.test',
                'phone' => '(85) 3000-2000',
                'city' => 'Fortaleza',
                'state' => 'CE',
                'source' => 'seed',
                'is_active' => true,
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
        );

        Product::query()->updateOrCreate(
            ['code' => 'EQ-001'],
            [
                'name' => 'Monitor Multiparametrico',
                'description' => 'Equipamento para acompanhamento continuo de sinais vitais.',
                'category' => 'Equipamentos',
                'unit' => 'un',
                'base_price' => 12990.00,
                'status' => 'active',
                'source' => 'seed',
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
        );

        Product::query()->updateOrCreate(
            ['code' => 'SV-010'],
            [
                'name' => 'Instalacao Tecnica',
                'description' => 'Servico de instalacao e configuracao inicial.',
                'category' => 'Servicos',
                'unit' => 'servico',
                'base_price' => 1800.00,
                'status' => 'active',
                'source' => 'seed',
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
        );

        Product::query()->updateOrCreate(
            ['code' => 'SUP-021'],
            [
                'name' => 'Kit de Sensores',
                'description' => 'Conjunto de sensores e acessorios compativeis com o monitor.',
                'category' => 'Acessorios',
                'unit' => 'kit',
                'base_price' => 950.00,
                'status' => 'active',
                'source' => 'seed',
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
        );

        ProposalTemplate::query()->updateOrCreate(
            ['code' => 'PADRAO-COMERCIAL'],
            [
                'name' => 'Template Comercial Padrao',
                'version' => '1.0',
                'description' => 'Modelo base para propostas comerciais do time.',
                'is_default' => true,
                'is_active' => true,
            ],
        );
    }
}
