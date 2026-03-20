<?php

namespace App\Enums;

enum AuditActionEnum: string
{
    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';
    case EXPORTED = 'exported';
    case STATUS_CHANGED = 'status_changed';
}
