<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config/file_storage.php';

$page_title = "Dashboard - Grievance System";
include 'includes/header.php';

$grievances = getGrievancesByUserId($_SESSION['user_id']);
$notifications = getNotificationsByUserId($_SESSION['user_id']);

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    $grievance_id = (int)$_POST['grievance_id'];
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    if ($rating >= 1 && $rating <= 5) {
        $feedback = readData(FEEDBACK_FILE);
        $feedback[] = [
            'grievance_id' => $grievance_id,
            'rating' => $rating,
            'comment' => $comment,
            'created_at' => date('Y-m-d H:i:s')
        ];
        writeData(FEEDBACK_FILE, $feedback);
        addNotification($_SESSION['user_id'], "Feedback submitted for grievance #$grievance_id.");
        header("Location: index.php");
        exit();
    }
}
?>
<div class="flex min-h-screen">
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex-1 p-6">
        <h1 class="text-4xl font-extrabold mb-8 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'text-white' : 'text-primary'; ?>">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></h1>
        
        <!-- Notifications -->
        <?php if (!empty($notifications)) { ?>
            <div class="mb-8 p-6 rounded-xl shadow-lg <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-800 text-gray-200' : 'bg-white text-gray-700'; ?>">
                <h2 class="text-2xl font-bold mb-4 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'text-white' : 'text-primary'; ?>">Notifications</h2>
                <ul class="space-y-2">
                    <?php foreach ($notifications as $n) { ?>
                        <li class="<?php echo $n['read'] ? '' : 'font-bold'; ?>"><?php echo htmlspecialchars($n['message']); ?> (<?php echo $n['created_at']; ?>)</li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <!-- Grievances -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'text-white' : 'text-primary'; ?>">Your Grievances</h2>
            <div class="flex space-x-4 mb-4">
                <input type="text" id="search" placeholder="Search grievances..." class="p-2 border rounded-lg <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-700 text-white border-gray-600' : 'bg-white text-gray-900 border-gray-300'; ?>">
                <select id="statusFilter" class="p-2 border rounded-lg <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-700 text-white border-gray-600' : 'bg-white text-gray-900 border-gray-300'; ?>">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="resolved">Resolved</option>
                </select>
                <select id="sort" class="p-2 border rounded-lg <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-700 text-white border-gray-600' : 'bg-white text-gray-900 border-gray-300'; ?>">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                </select>
            </div>
            <div id="grievanceList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (empty($grievances)) { ?>
                    <p class="text-center <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'text-gray-400' : 'text-gray-500'; ?>">No grievances found.</p>
                <?php } else { ?>
                    <?php foreach ($grievances as $g) { ?>
                        <div class="p-6 rounded-xl shadow-lg transform hover:-translate-y-1 transition duration-300 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-800 text-gray-200' : 'bg-white text-gray-700'; ?>">
                            <h3 class="text-xl font-bold"><?php echo htmlspecialchars($g['category']); ?></h3>
                            <p class="mt-2"><?php echo htmlspecialchars($g['description']); ?></p>
                            <p class="mt-2">Status: <span class="<?php echo $g['status'] === 'pending' ? 'text-yellow-500' : 'text-green-500'; ?>"><?php echo htmlspecialchars($g['status']); ?></span></p>
                            <p class="mt-2">Submitted: <?php echo htmlspecialchars($g['submitted_at']); ?></p>
                            <?php if (isset($g['escalated']) && $g['escalated']) { ?>
                                <p class="mt-2 text-red-500">Escalated</p>
                            <?php } ?>
                            <?php if ($g['file_path']) { ?>
                                <a href="<?php echo $g['file_path']; ?>" class="text-blue-500 hover:underline" target="_blank">View Attachment</a>
                            <?php } ?>
                            <?php if ($g['status'] === 'pending' && (!isset($g['escalated']) || !$g['escalated'])) { ?>
                                <form method="POST" action="escalate.php" class="mt-4">
                                    <input type="hidden" name="grievance_id" value="<?php echo $g['id']; ?>">
                                    <button type="submit" name="escalate" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">Escalate</button>
                                </form>
                            <?php } ?>
                            <?php if ($g['status'] === 'pending') { ?>
                                <form method="POST" action="index.php" class="mt-4">
                                    <input type="hidden" name="grievance_id" value="<?php echo $g['id']; ?>">
                                    <label class="block text-gray-700">Rating (1-5):</label>
                                    <input type="number" name="rating" min="1" max="5" class="p-2 border rounded-lg w-full <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-700 text-white border-gray-600' : 'bg-white text-gray-900 border-gray-300'; ?>" required>
                                    <label class="block text-gray-700 mt-2">Comment:</label>
                                    <textarea name="comment" class="p-2 border rounded-lg w-full <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-700 text-white border-gray-600' : 'bg-white text-gray-900 border-gray-300'; ?>"></textarea>
                                    <button type="submit" name="submit_feedback" class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-primary mt-2">Submit Feedback</button>
                                </form>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
    const grievances = <?php echo json_encode($grievances); ?>;
    function filterGrievances() {
        const search = document.getElementById('search').value.toLowerCase();
        const status = document.getElementById('statusFilter').value;
        const sort = document.getElementById('sort').value;
        let filtered = grievances.filter(g => 
            (g.category.toLowerCase().includes(search) || g.description.toLowerCase().includes(search)) &&
            (!status || g.status === status)
        );
        if (sort === 'newest') {
            filtered.sort((a, b) => new Date(b.submitted_at) - new Date(a.submitted_at));
        } else {
            filtered.sort((a, b) => new Date(a.submitted_at) - new Date(b.submitted_at));
        }
        const list = document.getElementById('grievanceList');
        list.innerHTML = '';
        if (filtered.length === 0) {
            list.innerHTML = '<p class="text-center <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'text-gray-400' : 'text-gray-500'; ?>">No grievances found.</p>';
        } else {
            filtered.forEach(g => {
                const div = document.createElement('div');
                div.className = 'p-6 rounded-xl shadow-lg transform hover:-translate-y-1 transition duration-300 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-800 text-gray-200' : 'bg-white text-gray-700'; ?>';
                div.innerHTML = `
                    <h3 class="text-xl font-bold">${g.category}</h3>
                    <p class="mt-2">${g.description}</p>
                    <p class="mt-2">Status: <span class="${g.status === 'pending' ? 'text-yellow-500' : 'text-green-500'}">${g.status}</span></p>
                    <p class="mt-2">Submitted: ${g.submitted_at}</p>
                    ${g.escalated ? '<p class="mt-2 text-red-500">Escalated</p>' : ''}
                    ${g.file_path ? `<a href="${g.file_path}" class="text-blue-500 hover:underline" target="_blank">View Attachment</a>` : ''}
                `;
                list.appendChild(div);
            });
        }
    }
    document.getElementById('search').addEventListener('input', filterGrievances);
    document.getElementById('statusFilter').addEventListener('change', filterGrievances);
    document.getElementById('sort').addEventListener('change', filterGrievances);
</script>
<?php include 'includes/footer.php'; ?>