<?php

namespace Core\Database;

abstract class Migration
{
    abstract public function up(): void;
    abstract public function down(): void;

    protected function execute(string $sql): void
    {
        $db = Database::getDBInstance();
        $db->execute('SET FOREIGN_KEY_CHECKS = 0');
        $db->execute($sql);
        $db->execute('SET FOREIGN_KEY_CHECKS = 1');
    }
}