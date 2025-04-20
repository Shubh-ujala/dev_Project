<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}
include 'config/file_storage.php';

$page_title = "Admin Dashboard - Grievance System";
include 'includes/header.php';

// Handle grievance closure
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['close_grievance'])) {
    $grievance_id = (int)$_POST['grievance_id'];
    $grievances = readData(GRIEVANCES_FILE);
    $found = false;
    foreach ($grievances as &$g) {
        if ($g['id'] === $grievance_id && $g['status'] !== 'resolved') {
            $g['status'] = 'resolved';
            $g['resolved_at'] = date('Y-m-d H:i:s');
            if (!$g['anonymous'] && isset($g['user_id'])) {
                addNotification($g['user_id'], "Your grievance #$grievance_id has been resolved.");
            }
            $found = true;
            break;
        }
    }
    if ($found) {
        writeData(GRIEVANCES_FILE, $grievances);
    }
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch all grievances
$grievances = readData(GRIEVANCES_FILE);

// Calculate analytics
$total_grievances = count($grievances);
$pending_grievances = count(array_filter($grievances, function($g) {
    return $g['status'] === 'pending';
}));
$resolved_grievances = count(array_filter($grievances, function($g) {
    return $g['status'] === 'resolved';
}));
$escalated_grievances = count(array_filter($grievances, function($g) {
    return isset($g['escalated']) && $g['escalated'];
}));
?>
<div class="flex min-h-screen">
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex-1 p-6">
        <h1 class="text-4xl font-extrabold mb-8 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'text-white' : 'text-primary'; ?>">Admin Dashboard</h1>
        
        <!-- Analytics -->
            <div class="flex flex-wrap gap-6 mb-8">
            <div class="flex-1 min-w-[220px] p-6 rounded-xl shadow-lg <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-800 text-gray-200' : 'bg-white text-gray-700'; ?>">
                <h2 class="text-xl font-bold">Total Grievances</h2>
                <p class="text-3xl"><?php echo $total_grievances; ?></p>
            </div>
            <div class="flex-1 min-w-[220px] p-6 rounded-xl shadow-lg <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-800 text-gray-200' : 'bg-white text-gray-700'; ?>">
                <h2 class="text-xl font-bold">Pending</h2>
                <p class="text-3xl"><?php echo $pending_grievances; ?></p>
            </div>
            <div class="flex-1 min-w-[220px] p-6 rounded-xl shadow-lg <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-800 text-gray-200' : 'bg-white text-gray-700'; ?>">
                <h2 class="text-xl font-bold">Resolved</h2>
                <p class="text-3xl"><?php echo $resolved_grievances; ?></p>
            </div>
            <div class="flex-1 min-w-[220px] p-6 rounded-xl shadow-lg <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-800 text-gray-200' : 'bg-white text-gray-700'; ?>">
                <h2 class="text-xl font-bold">Escalated</h2>
                <p class="text-3xl"><?php echo $escalated_grievances; ?></p>
            </div>
        </div>

        <!-- Grievances Table -->
        <div class="p-6 rounded-xl shadow-lg <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-800 text-gray-200' : 'bg-white text-gray-700'; ?>">
            <h2 class="text-2xl font-bold mb-4 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'text-white' : 'text-primary'; ?>">All Grievances</h2>
            <?php if (empty($grievances)) { ?>
                <p class="text-center <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'text-gray-400' : 'text-gray-500'; ?>">No grievances found.</p>
            <?php } else { ?>
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-700' : 'bg-gray-100'; ?>">
                            <th class="p-3 text-left">ID</th>
                            <th class="p-3 text-left">User Email</th>
                            <th class="p-3 text-left">Category</th>
                            <th class="p-3 text-left">Description</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Submitted At</th>
                            <th class="p-3 text-left">Escalated</th>
                            <th class="p-3 text-left">Anonymous</th>
                            <th class="p-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grievances as $g) {
                            $user = $g['anonymous'] || !isset($g['user_id']) ? null : getUserById($g['user_id']);
                            $user_email = $user ? $user['email'] : 'Anonymous';
                        ?>
                            <tr class="border-b ">
                                <td class="p-3"><?php echo htmlspecialchars($g['id']); ?></td>
                                <td class="p-3"><?php echo htmlspecialchars($user_email); ?></td>
                                <td class="p-3"><?php echo htmlspecialchars($g['category']); ?></td>
                                <td class="p-3"><?php echo htmlspecialchars($g['description']); ?></td>
                                <td class="p-3 <?php echo $g['status'] === 'pending' ? 'text-yellow-500' : 'text-green-500'; ?>">
                                    <?php echo htmlspecialchars($g['status']); ?>
                                </td>
                                <td class="p-3"><?php echo htmlspecialchars($g['submitted_at']); ?></td>
                                <td class="p-3"><?php echo isset($g['escalated']) && $g['escalated'] ? 'Yes' : 'No'; ?></td>
                                <td class="p-3"><?php echo $g['anonymous'] ? 'Yes' : 'No'; ?></td>
                                <td class="p-3">
                                    <?php if ($g['status'] !== 'resolved') { ?>
                                        <form method="POST" action="admin_dashboard.php">
                                            <input type="hidden" name="grievance_id" value="<?php echo $g['id']; ?>">
                                            <button type="submit" name="close_grievance" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600">Close</button>
                                        </form>
                                    <?php } else { ?>
                                        <span class="text-gray-500">Closed</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>