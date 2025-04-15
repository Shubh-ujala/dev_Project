<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config/file_storage.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $description = $_POST['description'];
    $anonymous = isset($_POST['anonymous']) ? true : false;
    $file_path = '';

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $upload_dir = 'assets/uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_path = $upload_dir . basename($_FILES['attachment']['name']);
        move_uploaded_file($_FILES['attachment']['tmp_name'], $file_path);
    }

    $grievances = readData(GRIEVANCES_FILE);
    $grievance_id = count($grievances) + 1;

    $new_grievance = [
        'id' => $grievance_id,
        'user_id' => $anonymous ? null : $_SESSION['user_id'],
        'category' => $category,
        'description' => $description,
        'status' => 'pending',
        'file_path' => $file_path,
        'submitted_at' => date('Y-m-d H:i:s'),
        'anonymous' => $anonymous
    ];

    $grievances[] = $new_grievance;
    writeData(GRIEVANCES_FILE, $grievances);
    if (!$anonymous) {
        addNotification($_SESSION['user_id'], "Your grievance #$grievance_id has been submitted.");
    }

    header("Location: index.php");
    exit();
}

$page_title = "Submit Grievance - Grievance System";
include 'includes/header.php';
?>
<div class="flex min-h-screen">
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex-1 p-6">
        <h1 class="text-4xl font-extrabold mb-8 text-primary">Submit a Grievance</h1>
        <form action="grievance.php" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-xl shadow-2xl card-3d">
            <div class="mb-6">
                <label class="block text-gray-700 font-medium">Category</label>
                <?php $categories = ['Health', 'Education', 'Traffic', 'Billing', 'Technical Support']; ?>
                <select name="category" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary" required>
                    <?php foreach ($categories as $cat) {
                        echo '<option value="' . $cat . '">' . $cat . '</option>';
                    } ?>
                </select>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-medium">Description</label>
                <textarea name="description" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary" rows="5" required></textarea>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-medium">Attachment</label>
                <input type="file" name="attachment" class="w-full p-3 border rounded-lg">
            </div>
            <div class="mb-6">
                <input type="checkbox" name="anonymous" id="anonymous" class="mr-2">
                <label for="anonymous" class="text-gray-700 font-medium">Submit Anonymously</label>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-primary to-secondary text-white p-3 rounded-lg hover:opacity-90 transition duration-300">Submit Grievance</button>
        </form>
        <div class="mt-6 bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-2xl font-bold text-primary">Suggested Resolution</h2>
            <p class="text-gray-700 mt-2">For similar issues, try contacting support directly or checking the FAQ.</p>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>