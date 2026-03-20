<?php

namespace App\Actions\Import;

use App\Services\ExcelMappingService;

class ImportClientsAction
{
    public function __construct(private readonly ExcelMappingService $mappingService)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function execute(string $storedFilePath): array
    {
        return [
            'stored_file_path' => $storedFilePath,
            'mapping' => $this->mappingService->clientColumns(),
            'imported' => 0,
            'updated' => 0,
            'ignored' => 0,
            'errors' => [],
        ];
    }
}
