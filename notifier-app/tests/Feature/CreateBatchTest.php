<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateBatchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_it_creates_batch_and_messages(): void
    {
        $this->withHeader('X-Idempotency-Key', 'test-key')->postJson('/v1/notifications', [
            'channel' => 'email',
            'priority' => 'transactional',
            'subject' => 'Hello',
            'body' => 'World',
            'recipient_ids' => [1, 2, 3],
        ])->assertStatus(201);

        $this->assertDatabaseHas('notification_batches', [
            'idempotency_key' => 'test-key',
            'status' => 'pending',
        ]);

        $this->assertDatabaseCount('notification_messages', 3);
    }

    public function test_try_to_store_multiple_by_same_idempotency_key()
    {
        $payload = [
            'channel' => 'email',
            'priority' => 'transactional',
            'subject' => 'Hello',
            'body' => 'World',
            'recipient_ids' => [1, 2, 3],
        ];

        $this->withHeader('X-Idempotency-Key', 'test-key')
            ->postJson('/v1/notifications', $payload)
            ->assertStatus(201);

        $this->withHeader('X-Idempotency-Key', 'test-key')
            ->postJson('/v1/notifications', $payload)
            ->assertStatus(409);

        $this->assertDatabaseCount('notification_batches', 1);

        $this->assertDatabaseHas('notification_batches', [
            'idempotency_key' => 'test-key',
        ]);

        $this->assertDatabaseCount('notification_messages', 3);
    }
}
