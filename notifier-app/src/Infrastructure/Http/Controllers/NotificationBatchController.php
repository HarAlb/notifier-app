<?php

declare(strict_types=1);

namespace Src\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OAT;
use Ramsey\Uuid\Uuid;
use Src\Application\NotificationBatch\CreateBatch\CreateBatchCommand;
use Src\Application\NotificationBatch\CreateBatch\CreateBatchHandler;
use Src\Domain\NotificationBatch\ValueObjects\Priority;
use Src\Infrastructure\Http\Requests\StoreNotificationBatchRequest;
use Src\Infrastructure\Http\Resources\NotificationBatchResource;

class NotificationBatchController extends Controller
{
    #[
        OAT\Post(
            path: '/v1/notifications',
            operationId: 'storeNotification',
            summary: 'Store Notification Batch',
            requestBody: new OAT\RequestBody(
                required: true,
                content: new OAT\JsonContent(
                    ref: '#/components/schemas/NotificationBatchStoreRequest'
                )
            ),
            tags: ['Notification'],
            parameters: [
                new OAT\Parameter(
                    name: 'X-Idempotency-Key',
                    description: 'Idempotency key',
                    in: 'header',
                    required: true,
                    schema: new OAT\Schema(
                        type: 'string',
                        format: 'uuid'
                    ),
                    example: '019e35c8-dc6e-746e-9a06-d47749b70459'
                )
            ],
            responses: [
                new OAT\Response(
                    response: 200,
                    description: 'OK',
                    content: new OAT\JsonContent(ref: '#/components/schemas/NotificationBatchResponseWrapper')
                ),
                new OAT\Response(response: 422, description: 'Validation', content: new OAT\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')),
            ]
        )
    ]
    public function store(StoreNotificationBatchRequest $request, CreateBatchHandler $handler): NotificationBatchResource
    {
        $command = new CreateBatchCommand(
            id: Uuid::uuid4(),
            idempotencyKey: $request->header('X-Idempotency-Key'),
            channel: $request->input('channel'),
            subject: $request->input('subject'),
            body: $request->input('body'),
            priority: $request->input('priority', Priority::MARKETING->value),
            recipientIds: $request->input('recipient_ids', [])
        );

        return NotificationBatchResource::make($handler->handle($command));
    }
}
