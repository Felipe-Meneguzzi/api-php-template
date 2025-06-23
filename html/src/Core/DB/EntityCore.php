<?php
declare(strict_types=1);

namespace App\Core\DB;

class EntityCore {

    public static function getTable(): string {
        return $this->table;
    }
}