<?php
session_start();
include 'config/file_storage.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: register.php");
        exit();
    }

    $users = readData(USERS_FILE);
    if (getUserByEmail($email)) {
        $_SESSION['error'] = "Email already exists.";
        header("Location: register.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $user_id = count($users) + 1;

    $new_user = [
        'id' => $user_id,
        'full_name' => $full_name,
        'email' => $email,
        'phone' => $phone,
        'password' => $hashed_password,
        'is_admin' => count($users) === 0 ? true : false, // First user is admin
        'created_at' => date('Y-m-d H:i:s')
    ];

    $users[] = $new_user;
    writeData(USERS_FILE, $users);

    header("Location: login.php");
    exit();
}
?>