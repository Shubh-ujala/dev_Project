<?php
session_start();
include 'config/file_storage.php';
$page_title = "Public Grievance Board";
include 'includes/header.php';
$grievances = array_filter(readData(GRIEVANCES_FILE), function($g) { return !$g['anonymous']; });
?>
<div class="flex min-h-screen">
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex-1 p-6">
        <h1 class="text-4xl font-extrabold mb-8 text-primary">Public Grievance Board</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($grievances as $g) {
                echo '<div class="bg-white p-6 rounded-xl shadow-lg card-3d">';
                echo '<h3 class="font-bold text-lg text-primary">' . htmlspecialchars($g['category']) . '</h3>';
                echo '<p class="text-gray-700 mt-2">' . htmlspecialchars($g['description']) . '</p>';
                echo '<p class="text-sm text-gray-500 mt-2">Status: ' . $g['status'] . '</p>';
                echo '</div>';
            } ?>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>