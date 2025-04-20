<?php
session_start();
$page_title = "Login - Grievance System";
include 'includes/header.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $user = getUserByEmail($email);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['is_admin'] = isset($user['is_admin']) && $user['is_admin'] === true;
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: login.php");
        exit();
    }
}
?>
<div class="flex items-center justify-center min-h-screen px-4">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md transform transition-all hover:scale-105">
        <h2 class="text-3xl font-extrabold mb-6 text-center text-primary">Login</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="text-red-500 mb-4 text-center">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="login_process.php" method="POST">
            <div class="mb-6">
                <label class="block text-gray-700 font-medium">Email</label>
                <input type="email" name="email" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-medium">Password</label>
                <input type="password" name="password" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary" required>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-primary to-secondary text-white p-3 rounded-lg hover:opacity-90 transition duration-300">Login</button>
        </form>
        <p class="mt-6 text-center text-gray-600">
            <a href="#" class="text-secondary hover:underline">Forgot Password?</a> | 
            <a href="register.php" class="text-secondary hover:underline">Register</a>
        </p>
    </div>
</div>
<!-- <?php include 'includes/footer.php'; ?> -->