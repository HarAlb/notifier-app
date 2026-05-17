<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DispatchBatchTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_dispatch_batch_changes_status_and_publishes_messages(): void
    {
        Queue::fake();

        $batch = NotificationBatchHelper::createBatchWithMessages(3);

        $this->postJson("/api/batches/{$batch->getId()}/dispatch");

        $this->assertDatabaseHas('notification_batches', [
            'id' => $batch->getId()->toString(),
            'status' => 'dispatched',
        ]);

        Queue::assertPushed(SendEmailJob::class);
    }
}
