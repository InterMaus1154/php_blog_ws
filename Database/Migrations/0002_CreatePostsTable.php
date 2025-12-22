<?php

namespace Database\Migrations;

use Database\Migration;

return new class extends Migration{
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS posts(
                post_id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                post_title VARCHAR(100) NOT NULL,
                post_slug VARCHAR(150) NOT NULL,
                post_context TEXT NOT NULL,
                post_hidden BOOL NOT NULL DEFAULT FALSE,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(user_id)
            )
        ");
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS posts");
    }
};