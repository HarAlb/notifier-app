<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence;

use Illuminate\Support\Facades\DB;
use Src\Application\Shared\Contracts\TransactionServiceInterface;

final class TransactionService implements TransactionServiceInterface
{
    #[\Override]
    public function run(\Closure $callback): mixed
    {
        return DB::transaction($callback);
    }

    #[\Override]
    public function begin(): void
    {
        DB::beginTransaction();
    }

    #[\Override]
    public function commit(): void
    {
        DB::commit();
    }

    #[\Override]
    public function rollback(): void
    {
        DB::rollBack();
    }
}
