<?php

namespace App\Database;

use PDO;

class Database
{
    public function __construct(
        private array $config
    ) {}
    public function connect(): PDO
    {
        return new PDO(
            "mysql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['database']}",
            $this->config['username'],
            $this->config['password']
        );
    }
}
