<?php

declare(strict_types=1);

namespace Src\Domain\NotificationBatch\ValueObjects;

enum Priority: string
{
    case TRANSACTIONAL = 'transactional';
    case CRITICAL = 'critical';
    case MARKETING = 'marketing';
}
