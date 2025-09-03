Для создания таблиц в БД (mysql)

CREATE DATABASE todo_db;
USE todo_db;
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending', -- e.g., pending, in_progress, completed
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
