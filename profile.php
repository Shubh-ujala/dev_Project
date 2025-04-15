<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config/file_storage.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);

    $users = readData(USERS_FILE);
    foreach ($users as &$user) {
        if ($user['id'] === $_SESSION['user_id']) {
            $user['full_name'] = $full_name;
            $user['phone'] = $phone;
            $_SESSION['full_name'] = $full_name;
            break;
        }
    }
    writeData(USERS_FILE, $users);
}

$user = getUserById($_SESSION['user_id']);
$page_title = "Profile - Grievance System";
include 'includes/header.php';
?>
<div class="flex min-h-screen">
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex-1 p-6">
        <h1 class="text-4xl font-extrabold mb-8 text-primary">Profile</h1>
        <div class="bg-white p-6 rounded-xl shadow-2xl mb-8 card-3d">
            <form action="profile.php" method="POST">
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium">Full Name</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium">Email</label>
                    <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full p-3 border rounded-lg" disabled>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium">Phone</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary" required>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-primary to-secondary text-white p-3 rounded-lg hover:opacity-90 transition duration-300">Update Profile</button>
            </form>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>