<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch\CreateBatch;

use Illuminate\Database\UniqueConstraintViolationException;
use Ramsey\Uuid\Uuid;
use Src\Application\NotificationBatch\AsyncDispatcherInterface;
use Src\Application\Shared\Contracts\TransactionServiceInterface;
use Src\Domain\NotificationBatch\Entities\NotificationBatch;
use Src\Domain\NotificationBatch\Entities\NotificationMessage;
use Src\Domain\NotificationBatch\NotificationBatchRepositoryInterface;
use Src\Domain\NotificationBatch\NotificationMessageRepositoryInterface;
use Src\Domain\NotificationBatch\ValueObjects\Body;
use Src\Domain\NotificationBatch\ValueObjects\Channel;
use Src\Domain\NotificationBatch\ValueObjects\IdempotencyKey;
use Src\Domain\NotificationBatch\ValueObjects\Priority;
use Src\Domain\NotificationBatch\ValueObjects\Subject;

final readonly class CreateBatchHandler
{
    public function __construct(
        private NotificationBatchRepositoryInterface $repository,
        private NotificationMessageRepositoryInterface $messageRepository,
        private TransactionServiceInterface $transactionService,
        private AsyncDispatcherInterface $asyncDispatcher
    ) {}

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

        $messages = [];

        foreach ($command->recipientIds as $recipientId) {
            $messages[] = NotificationMessage::createForBatch(Uuid::uuid4(), $batch->getId(), $recipientId);
        }

        $this->transactionService->begin();

        try {
            $this->repository->save($batch);

            $this->messageRepository->saveMany($messages);

            $this->transactionService->commit();
        } catch (UniqueConstraintViolationException $constraintViolationException) {
            $batch = $this->repository->findByIdempotencyKey($idempotencyKey);
            /** @psalm-assert NotificationBatch $batch */
            \assert($batch !== null);

            return $batch;
        } catch (\Exception $exception) {
            $this->transactionService->rollback();

            throw $exception;
        }

        $this->asyncDispatcher->dispatchBatch($batch->getId(), $batch->getPriority());

        return $batch;
    }
}
