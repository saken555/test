<?php
// public/index.php

define('PROJECT_ROOT', dirname(__DIR__));

require_once PROJECT_ROOT . '/app/config.php';
require_once APPROOT . '/bootstrap.php';

$routes = [
    // Пользователи и Профиль
    'users/index'       => APPROOT . '/users/index.php',
    'users/dashboard'   => APPROOT . '/users/dashboard.php',
    'users/login'       => APPROOT . '/users/login.php',
    'users/agreement'   => APPROOT . '/users/agreement.php',
    'users/settings'    => APPROOT . '/users/settings.php',
    'users/add_edit'    => APPROOT . '/users/add_edit.php',
    'users/register'    => APPROOT . '/users/register.php',
    'users/actions'     => APPROOT . '/users/actions.php',
    'profile/dashboard' => APPROOT . '/users/dashboard.php',
    'profile/settings'  => APPROOT . '/users/settings.php',
    'profile/agreement' => APPROOT . '/users/agreement.php',

    // Другие модули
    'roles/index'       => APPROOT . '/roles/index.php',
    'roles/add_edit'    => APPROOT . '/roles/add_edit.php',
    'regions/index'     => APPROOT . '/regions/index.php',
    'regions/add_edit'  => APPROOT . '/regions/add_edit.php',
    'regions/actions'   => APPROOT . '/regions/actions.php',
    'cities/index'      => APPROOT . '/cities/index.php',
    'cities/add_edit'   => APPROOT . '/cities/add_edit.php',
    'cities/actions'    => APPROOT . '/cities/actions.php',
    'promocodes/index'      => APPROOT . '/promocodes/index.php',
    'promocodes/add_edit'       => APPROOT . '/promocodes/add_edit.php',
    'promocodes/actions'    => APPROOT . '/promocodes/actions.php',
    'networks/index'    => APPROOT . '/networks/index.php',
    'networks/add_edit' => APPROOT . '/networks/add_edit.php',
    'networks/actions'  => APPROOT . '/networks/actions.php',
    'clusters/index'    => APPROOT . '/clusters/index.php',
    'clusters/add_edit' => APPROOT . '/clusters/add_edit.php',
    'clusters/actions'  => APPROOT . '/clusters/actions.php',
    'modules/index'     => APPROOT . '/modules/index.php',
    'modules/add_edit'  => APPROOT . '/modules/add_edit.php',
    'modules/actions'   => APPROOT . '/modules/actions.php',
    'sales/mobile_list'     => APPROOT . '/sales/mobile_list.php',
    'sales/index'           => APPROOT . '/sales/index.php',
    'sales/actions'         => APPROOT . '/sales/actions.php',
    'sales/activations'     => APPROOT . '/sales/activations_list.php',
    'sales/confirm'         => APPROOT . '/sales/confirm.php',
    'products/index'      => APPROOT . '/products/index.php',
    'products/add_edit'   => APPROOT . '/products/add_edit.php',
    'products/actions'    => APPROOT . '/products/actions.php',

    // Ошибки
    'errors/403' => APPROOT . '/errors/403.php',
    'errors/404' => APPROOT . '/errors/404.php',
];

$page = $_GET['page'] ?? null;
$action = $_GET['action'] ?? 'index';

if ($page === null && isset($_SESSION['user_id'])) {
    header('Location: ' . URLROOT . '/index.php?page=profile&action=dashboard');
    exit();
} elseif ($page === null && !isset($_SESSION['user_id'])) {
    header('Location: ' . URLROOT . '/index.php?page=users&action=login');
    exit();
}

if ($page === null) {
    $page = 'users';
}

$route_key = $page . '/' . $action;

if ($action === 'add' || $action === 'edit') {
    $route_key = $page . '/add_edit';
} elseif (in_array($action, ['save', 'delete', 'logout', 'login_submit', 'register_submit', 'save_signature', 'reset_contract', 'download_contract', 'upload', 'save_confirmation', 'get_unactivated_promocodes'])) {
    $route_key = $page . '/actions';
}

if (array_key_exists($route_key, $routes)) {
    require_once $routes[$route_key];
} else {
    http_response_code(404);
    if (isset($routes['errors/404'])) {
        require_once $routes['errors/404'];
    } else {
        require_once APPROOT . '/layouts/header.php';
        ?>
        <div class="container" style="text-align: center; padding-top: 50px;">
            <h1>404 Страница не найдена</h1>
            <p>Маршрут "<?= htmlspecialchars($route_key) ?>" не существует, и страница ошибки 404 не определена.</p>
            <a href="<?= URLROOT; ?>/index.php?page=profile&action=dashboard" class="btn btn-primary">На главную</a>
        </div>
        <?php
        require_once APPROOT . '/layouts/footer.php';
    }
}
