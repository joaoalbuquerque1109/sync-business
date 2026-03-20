<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Import\ImportClientsAction;
use Illuminate\Http\Request;

class ExcelImportController extends Controller
{
    public function __construct(private readonly ImportClientsAction $importClientsAction)
    {
    }

    public function clients(Request $request)
    {
        return $this->success(
            $this->importClientsAction->execute($request->string('stored_file_path', 'storage/app/imports/clients.xlsx')->toString()),
            'Importação de clientes preparada com sucesso'
        );
    }

    public function products(Request $request)
    {
        return $this->success($request->all(), 'Importação de produtos preparada com sucesso');
    }

    public function proposalTemplate(Request $request)
    {
        return $this->success($request->all(), 'Importação de template de proposta preparada com sucesso');
    }

    public function proposalPrefill(Request $request)
    {
        return $this->success($request->all(), 'Pré-preenchimento de proposta preparado com sucesso');
    }
}
