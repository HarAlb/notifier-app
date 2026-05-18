<?php

declare(strict_types=1);

namespace Src\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OAT;
use Ramsey\Uuid\Uuid;
use Src\Application\NotificationBatch\GetMessages\GetMessagesCommand;
use Src\Application\NotificationBatch\GetMessages\GetMessagesHandler;
use Src\Infrastructure\Http\Resources\NotificationMessageResource;

class NotificationMessageController extends Controller
{
    #[
        OAT\Get(
            path: '/v1/notifications/{id}/messages',
            operationId: 'getMessagesData',
            summary: 'Get details data for message',
            tags: ['Notification'],
            responses: [
                new OAT\Response(
                    response: 200,
                    description: 'OK',
                    content: new OAT\JsonContent(ref: '#/components/schemas/NotificationMessageResponseWrapper')
                ),
            ]
        ),
        OAT\Parameter(
            name: 'id',
            description: 'ID',
            in: 'path',
            schema: new OAT\Schema(type: 'string'),
            example: 'c78b245f-ff62-4922-a525-1e924f8ce49b',
        ),
    ]
    public function index(string $id, GetMessagesHandler $handler): AnonymousResourceCollection
    {
        return NotificationMessageResource::collection(
            $handler->handle(
                new GetMessagesCommand(
                    Uuid::fromString($id)
                )
            )
        );
    }
}
