<?php

declare(strict_types=1);

namespace Src\Application\Shared\Contracts;

interface TransactionServiceInterface
{
    public function run(\Closure $callback): mixed;

    public function begin(): void;

    public function rollback(): void;

    public function commit(): void;
}
