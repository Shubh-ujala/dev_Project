<?php
define('USERS_FILE', __DIR__ . '/../data/users.json');
define('GRIEVANCES_FILE', __DIR__ . '/../data/grievances.json');
define('FEEDBACK_FILE', __DIR__ . '/../data/feedback.json');
define('NOTIFICATIONS_FILE', __DIR__ . '/../data/notifications.json');

if (!file_exists(__DIR__ . '/../data')) {
    mkdir(__DIR__ . '/../data', 0777, true);
}
foreach ([USERS_FILE, GRIEVANCES_FILE, FEEDBACK_FILE, NOTIFICATIONS_FILE] as $file) {
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
    }
}

function readData($file) {
    $data = file_get_contents($file);
    return json_decode($data, true) ?: [];
}

function writeData($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

function getUserByEmail($email) {
    $users = readData(USERS_FILE);
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}

function getUserById($id) {
    $users = readData(USERS_FILE);
    foreach ($users as $user) {
        if ($user['id'] === $id) {
            return $user;
        }
    }
    return null;
}

function getGrievancesByUserId($user_id) {
    $grievances = readData(GRIEVANCES_FILE);
    return array_filter($grievances, function($grievance) use ($user_id) {
        return $grievance['user_id'] === $user_id;
    });
}

function getNotificationsByUserId($user_id) {
    $notifications = readData(NOTIFICATIONS_FILE);
    return array_filter($notifications, function($n) use ($user_id) {
        return $n['user_id'] === $user_id;
    });
}

function addNotification($user_id, $message) {
    $notifications = readData(NOTIFICATIONS_FILE);
    $notifications[] = [
        'id' => count($notifications) + 1,
        'user_id' => $user_id,
        'message' => $message,
        'created_at' => date('Y-m-d H:i:s'),
        'read' => false
    ];
    writeData(NOTIFICATIONS_FILE, $notifications);
}
?>