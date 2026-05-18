<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch\GetMessages;

use Src\Domain\NotificationBatch\NotificationMessageRepositoryInterface;

final class GetMessagesHandler
{
    public function __construct(
        private readonly NotificationMessageRepositoryInterface $messageRepository,
    ) {}

    public function handle(GetMessagesCommand $command): array
    {
        return $this->messageRepository->findByBatchId($command->batchId);
    }
}
