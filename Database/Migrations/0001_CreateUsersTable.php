<?php

namespace Database\Migrations;

use Core\Database\Migration;

return new class extends Migration {

    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS users(
                user_id INT AUTO_INCREMENT PRIMARY KEY,
                user_fname VARCHAR(100) NOT NULL,
                user_lname VARCHAR(100) NOT NULL,
                user_username VARCHAR(50) NOT NULL UNIQUE,
                user_email VARCHAR(250) NOT NULL UNIQUE,
                user_password VARCHAR(255) NOT NULL,
                last_login TIMESTAMP,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP
            );
        ");
    }

    public function down(): void
    {
        $this->execute("
            DROP TABLE IF EXISTS users;
        ");
    }
};