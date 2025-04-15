<?php
session_start();
$page_title = "FAQ - Grievance System";
include 'includes/header.php';
?>
<div class="flex min-h-screen">
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex-1 p-6">
        <h1 class="text-4xl font-extrabold mb-8 text-primary">FAQ / Knowledge Base</h1>
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-2xl font-bold text-primary mb-4">Common Questions</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="font-semibold text-lg">How do I submit a grievance?</h3>
                    <p class="text-gray-700">Go to "Submit Grievance" in the sidebar, fill out the form, and click submit.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">How can I track my grievance?</h3>
                    <p class="text-gray-700">Check the "Dashboard" or "History" page for updates.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>