<?php
session_start();
$page_title = "Register - Grievance System";
include 'includes/header.php';
?>
<div class="flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="text-red-500 mb-4">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="register_process.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700">Full Name</label>
                <input type="text" name="full_name" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Phone</label>
                <input type="text" name="phone" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Confirm Password</label>
                <input type="password" name="confirm_password" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <input type="checkbox" name="terms" required>
                <label class="text-gray-700">I agree to the Terms & Conditions</label>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Register</button>
        </form>
        <p class="mt-4 text-center">
            <a href="login.php" class="text-blue-500">Already have an account? Login</a>
        </p>
    </div>
</div>
<?php include 'includes/footer.php'; ?>