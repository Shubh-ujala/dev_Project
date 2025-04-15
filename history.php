<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config/file_storage.php';
$page_title = "Grievance History - Grievance System";
include 'includes/header.php';

$grievances = getGrievancesByUserId($_SESSION['user_id']);
?>
<div class="flex min-h-screen">
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex-1 p-6">
        <h1 class="text-4xl font-extrabold mb-8 text-primary">Grievance History</h1>
        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
            <input type="text" id="searchHistory" onkeyup="filterHistory()" placeholder="Search history..." class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="historyList">
            <?php
            foreach ($grievances as $grievance) {
                echo '<div class="bg-white p-6 rounded-xl shadow-lg card-3d">';
                echo '<h3 class="font-bold text-lg text-primary">' . htmlspecialchars($grievance['category']) . '</h3>';
                echo '<p class="text-gray-700 mt-2">' . htmlspecialchars($grievance['description']) . '</p>';
                echo '<p class="text-sm text-gray-500 mt-2">Status: <span class="font-semibold ' . ($grievance['status'] === 'pending' ? 'text-yellow-500' : 'text-green-500') . '">' . htmlspecialchars($grievance['status']) . '</span></p>';
                echo '<p class="text-sm text-gray-500 mt-1">Submitted: ' . $grievance['submitted_at'] . '</p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>
<script>
    function filterHistory() {
        const input = document.getElementById('searchHistory').value.toLowerCase();
        const items = document.getElementById('historyList').getElementsByTagName('div');
        for (let i = 0; i < items.length; i++) {
            const text = items[i].textContent.toLowerCase();
            items[i].style.display = text.includes(input) ? '' : 'none';
        }
    }
</script>
<?php include 'includes/footer.php'; ?>