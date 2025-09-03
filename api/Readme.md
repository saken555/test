Для создания БД с таблицами (mysql)-

CREATE DATABASE todo_db;
USE todo_db;
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending', -- e.g., pending, in_progress, completed
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


как тестировать -
Создание задачи (POST)
    URL: http://your-site.com/tasks
    Method: POST

Получение всех задач (GET)
    URL: http://your-site.com/tasks
    Method: GET

Получение одной задачи (GET)
    URL: http://your-site.com/tasks/1 (где 1 - это id задачи)
    Method: GET

Удаление задачи (DELETE)
    URL: http://your-site.com/tasks/1
    Method: DELETE
