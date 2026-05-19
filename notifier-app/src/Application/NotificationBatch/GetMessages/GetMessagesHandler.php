<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch\GetMessages;

use Src\Domain\NotificationBatch\NotificationMessageRepositoryInterface;
use Src\Domain\NotificationBatch\NotificationMessageStatusHistoryRepositoryInterface;

final readonly class GetMessagesHandler
{
    public function __construct(
        private NotificationMessageRepositoryInterface              $messageRepository,
        private NotificationMessageStatusHistoryRepositoryInterface $historyRepository,
    )
    {
    }

    public function handle(GetMessagesCommand $command): array
    {
        $messages = $this->messageRepository->findByBatchId($command->batchId);

        $historyMap = $this->historyRepository->findByMessageIds(
            array_map(fn($m) => $m->getId(), $messages)
        );

        foreach ($messages as $message) {
            $history = $historyMap[$message->getId()->toString()] ?? [];

            foreach ($history as $historyItem) {
                $message->addToHistory($historyItem);
            }
        }

        return $messages;
    }
}
