<?php

namespace App\Database;

interface TransactionManagerInterface
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;

    public function run(callable $callback): mixed;
}