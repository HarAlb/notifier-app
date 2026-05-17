<?php

declare(strict_types=1);

namespace Src\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Attributes as OAT;
use Ramsey\Uuid\Uuid;
use Src\Application\NotificationBatch\CreateBatch\CreateBatchCommand;
use Src\Application\NotificationBatch\CreateBatch\CreateBatchHandler;
use Src\Domain\NotificationBatch\ValueObjects\Priority;
use Src\Infrastructure\Http\Requests\StoreNotificationBatchRequest;

class NotificationBatchController extends Controller
{
    #[
        OAT\Post(
            path: '/notifications',
            operationId: 'storeNotifications',
            summary: 'Register by password',
            requestBody: new OAT\RequestBody(
                required: true,
                content: new OAT\JsonContent(
                    ref: '#/components/schemas/RegisterRequestDoc'
                )
            ),
            tags: ['Auth'],
            responses: [
                new OAT\Response(
                    response: 200,
                    description: 'OK',
                    content: new OAT\JsonContent(ref: '#/components/schemas/AuthResponseDoc')
                ),
            ]
        )
    ]
    public function store(StoreNotificationBatchRequest $request, CreateBatchHandler $handler)
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

        return response()->json($handler->handle($command), 201);
    }
}
