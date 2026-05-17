<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch\CreateBatch;

use Src\Application\NotificationBatch\AsyncDispatcherInterface;
use Src\Application\Shared\Contracts\TransactionServiceInterface;
use Src\Domain\NotificationBatch\Entities\NotificationBatch;
use Src\Domain\NotificationBatch\NotificationBatchRepositoryInterface;
use Src\Domain\NotificationBatch\ValueObjects\Body;
use Src\Domain\NotificationBatch\ValueObjects\Channel;
use Src\Domain\NotificationBatch\ValueObjects\IdempotencyKey;
use Src\Domain\NotificationBatch\ValueObjects\Priority;
use Src\Domain\NotificationBatch\ValueObjects\Subject;

final readonly class CreateBatchHandler
{
    public function __construct(
        private NotificationBatchRepositoryInterface $repository,
        private TransactionServiceInterface          $transactionService,
        private AsyncDispatcherInterface $asyncDispatcher
    ) {
    }

    public function handle(CreateBatchCommand $command): NotificationBatch
    {
        $idempotencyKey = new IdempotencyKey($command->idempotencyKey);
        $existing = $this->repository->findByIdempotencyKey($idempotencyKey);

        if ($existing !== null) {
            throw new \DomainException('Batch with this idempotency key already exists');
        }

        $channel = new Channel($command->channel);
        $subject = $command->subject ? new Subject($command->subject) : null;
        $body = new Body($command->body);
        $priority = Priority::from($command->priority);

        $batch = NotificationBatch::create(
            $command->id,
            $idempotencyKey,
            $channel,
            $body,
            $priority,
            $subject,
        );

        $this->transactionService->begin();

        $this->repository->save($batch);

        $this->transactionService->commit();

        $this->asyncDispatcher->dispatchBatch($batch->getId());

        return $batch;
    }
}
