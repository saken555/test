<?php
// app/cities/index.php
// Этот файл отвечает за отображение списка городов.

// Функция getDbConnection() определена в core/database.php и доступна после подключения bootstrap.php.
// (bootstrap.php подключен в public/index.php)
$db = getDbConnection();

$cities = []; // Инициализируем массив для хранения данных о городах
try {
    // Запрос для выборки городов и их регионов.
    // Используем JOIN, чтобы получить название региона из таблицы 'regions'.
    $stmt = $db->prepare("SELECT c.id, c.name, r.name AS region_name
                                                FROM cities c
                                                JOIN regions r ON c.region_id = r.id
                                                ORDER BY c.name ASC"); // Сортируем по названию города для удобства
    $stmt->execute();
    $cities = $stmt->fetchAll(); // Получаем все результаты в виде ассоциативного массива
} catch (PDOException $e) {
    // В случае ошибки базы данных:
    // В реальном продакшене лучше логировать ошибку, а не выводить её на экран пользователя.
    // Например: error_log("Database Error in cities/index.php: " . $e->getMessage());
    echo "Произошла ошибка при загрузке данных о городах.";
    exit; // Прерываем выполнение скрипта
}

// Подключаем верхнюю часть общего макета (шапку сайта)
require_once APPROOT . '/layouts/header.php';
?>

<div class="container">
    <h1>Список городов</h1>

    <?php
    // Отображение Flash-сообщений (если они были установлены)
    // Эти функции определены в core/helpers.php
    display_flash_message('success_message', 'alert-success');
    display_flash_message('error_message', 'alert-danger');
    ?>

    <?php if (can_edit('cities')): // Проверяем право на редактирование для модуля 'cities'?>
        <a href="<?php echo URLROOT; ?>/index.php?page=cities&action=add" class="btn btn-primary mb-3">Добавить новый город</a>
    <?php endif; ?>

    <?php if (!empty($cities)): ?>
        <table id="cities-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Город</th>
                    <th>Регион</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cities as $city): ?>
                    <tr>
                        <td><?= htmlspecialchars($city['id']) ?></td>
                        <td><?= htmlspecialchars($city['name']) ?></td>
                        <td><?= htmlspecialchars($city['region_name']) ?></td>
                        <td>
                            <?php if (can_edit('cities')): // Проверяем право на редактирование для модуля 'cities'?>
                                <a href="<?php echo URLROOT; ?>/index.php?page=cities&action=edit&id=<?= $city['id'] ?>" class="btn btn-info btn-sm">Редактировать</a>
                                <button class="btn btn-danger btn-sm delete-city-btn" data-id="<?= $city['id'] ?>">Удалить</button>
                            <?php else: ?>
                                <span class="text-muted">Нет прав</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Города не найдены.</p>
    <?php endif; ?>
</div>

<?php
// Подключаем нижнюю часть общего макета (подвал сайта)
require_once APPROOT . '/layouts/footer.php';
?>

<script src="<?php echo URLROOT; ?>/assets/cities.js"></script>
