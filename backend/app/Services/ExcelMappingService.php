<?php

namespace App\Services;

class ExcelMappingService
{
    /**
     * @return array<string, array<string, string>>
     */
    public function clientColumns(): array
    {
        return [
            'cnpj' => ['CNPJ' => 'cnpj', 'Documento' => 'cnpj'],
            'company_name' => ['Razão Social' => 'company_name', 'Razao Social' => 'company_name'],
            'trade_name' => ['Nome Fantasia' => 'trade_name'],
            'primary_contact_name' => ['Decisor' => 'primary_contact_name', 'Contato' => 'primary_contact_name'],
            'billing_range' => ['Faixa de Faturamento' => 'billing_range'],
            'phone' => ['Telefone' => 'phone'],
            'email' => ['Email' => 'email', 'E-mail' => 'email'],
            'state' => ['UF' => 'state'],
            'city' => ['Cidade' => 'city'],
            'microregion' => ['Microrregião' => 'microregion', 'Microrregiao' => 'microregion'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function proposalTemplateMap(): array
    {
        return [
            'sheets' => [
                'cover' => ['Rosto', 'Folha Rosto'],
                'calculations' => ['Cálculos', 'Calculos'],
                'clients' => ['Dados Cliente'],
                'commercial_base' => ['Base Dados'],
            ],
        ];
    }
}
