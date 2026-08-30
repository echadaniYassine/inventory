<?php

namespace App\Database;

use PDO;
use Throwable;

class PdoTransactionManager implements TransactionManagerInterface
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function begin(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        $this->pdo->commit();
    }

    public function rollback(): void
    {
        $this->pdo->rollBack();
    }
    public function run(callable $callback): mixed
    {
        $this->begin();

        try {
            $result = $callback();

            $this->commit();

            return $result;
        } catch (Throwable $e) {
            $this->rollback();

            throw $e;
        }
    }
}
