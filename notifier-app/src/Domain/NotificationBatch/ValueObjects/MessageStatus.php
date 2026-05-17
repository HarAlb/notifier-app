<?php

namespace Src\Domain\NotificationBatch\ValueObjects;

enum MessageStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case SENT = 'sent';
    case FAILED = 'failed';
}
