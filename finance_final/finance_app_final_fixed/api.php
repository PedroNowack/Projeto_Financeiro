<?php
session_start();
header('Content-Type: application/json');

$data_dir = __DIR__ . '/data';
if (!file_exists($data_dir)) {
    mkdir($data_dir, 0777, true);
}

$users_file = $data_dir . '/users.json';
if (!file_exists($users_file)) {
    $initial_users = [
        'admin' => ['id' => 1, 'username' => 'admin', 'password' => '123456', 'salary' => 1600.00, 'categories' => [
            ['name' => 'Finanças', 'percentage' => 10, 'color' => '#ffffff'],
            ['name' => 'Lazer', 'percentage' => 30, 'color' => '#888888'],
            ['name' => 'Sobrevivência', 'percentage' => 60, 'color' => '#444444']
        ]]
    ];
    file_put_contents($users_file, json_encode($initial_users));
}

function getUsers() {
    global $users_file;
    return json_decode(file_get_contents($users_file), true);
}

function saveUsers($users) {
    global $users_file;
    file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT));
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($action === 'login' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';

    $users = getUsers();
    if (isset($users[$username]) && $users[$username]['password'] === $password) {
        $_SESSION['user_id'] = $users[$username]['id'];
        $_SESSION['username'] = $username;
        echo json_encode(['status' => 'success', 'user' => ['username' => $username]]);
    } else if (!empty($username) && !empty($password)) {
        // Auto-registro simples
        $new_id = count($users) + 1;
        $users[$username] = [
            'id' => $new_id,
            'username' => $username,
            'password' => $password,
            'salary' => 0,
            'categories' => []
        ];
        saveUsers($users);
        $_SESSION['user_id'] = $new_id;
        $_SESSION['username'] = $username;
        echo json_encode(['status' => 'success', 'user' => ['username' => $username]]);
    } else {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Inválido']);
    }
    exit;
}

if ($action === 'logout') {
    session_destroy();
    echo json_encode(['status' => 'success']);
    exit;
}

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Não autorizado']);
    exit;
}

$username = $_SESSION['username'];
$users = getUsers();

if ($method === 'GET') {
    $user_data = $users[$username];
    echo json_encode([
        'username' => $username,
        'salary' => $user_data['salary'] ?? 0,
        'categories' => $user_data['categories'] ?? []
    ]);
} elseif ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['salary'])) { $users[$username]['salary'] = $input['salary']; }
    if (isset($input['categories'])) { $users[$username]['categories'] = $input['categories']; }
    saveUsers($users);
    echo json_encode(['status' => 'success']);
}
?>
