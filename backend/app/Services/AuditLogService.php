<?php

namespace App\Services;

use App\Enums\AuditActionEnum;

class AuditLogService
{
    public function buildPayload(AuditActionEnum $action, string $entityType, int|string|null $entityId, array $before = [], array $after = []): array
    {
        return [
            'action' => $action->value,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'before_data' => $before,
            'after_data' => $after,
        ];
    }
}
