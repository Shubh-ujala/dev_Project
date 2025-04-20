<aside class="transition-colors duration-300 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-800 text-gray-200' : 'bg-gradient-to-b from-primary to-secondary text-white'; ?> w-64 h-screen p-6 fixed md:relative" id="sidebar">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Grievance System</h2>
        <button class="md:hidden text-white" onclick="toggleSidebar()">☰</button>
    </div>
    <nav>
        <ul class="space-y-4">
            <li><a href="index.php" class="block py-2 px-4 rounded-lg transition duration-300 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'hover:bg-gray-700 hover:text-white' : 'hover:bg-white hover:text-primary'; ?>">Dashboard</a></li>
            <li><a href="grievance.php" class="block py-2 px-4 rounded-lg transition duration-300 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'hover:bg-gray-700 hover:text-white' : 'hover:bg-white hover:text-primary'; ?>">Submit Grievance</a></li>
            <li><a href="profile.php" class="block py-2 px-4 rounded-lg transition duration-300 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'hover:bg-gray-700 hover:text-white' : 'hover:bg-white hover:text-primary'; ?>">Profile</a></li>
            <li><a href="history.php" class="block py-2 px-4 rounded-lg transition duration-300 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'hover:bg-gray-700 hover:text-white' : 'hover:bg-white hover:text-primary'; ?>">History</a></li>
            <li><a href="faq.php" class="block py-2 px-4 rounded-lg transition duration-300 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'hover:bg-gray-700 hover:text-white' : 'hover:bg-white hover:text-primary'; ?>">FAQ</a></li>
            <li><a href="public_board.php" class="block py-2 px-4 rounded-lg transition duration-300 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'hover:bg-gray-700 hover:text-white' : 'hover:bg-white hover:text-primary'; ?>">Public Board</a></li>


            <!-- ensure that the logged_user is admin if the logged-user is admin then only the he/she will be able to see the Admin Dashboard -->
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) { ?>
                <li>
                    <a href="admin_dashboard.php" class="block py-2 px-4 rounded-lg transition duration-300 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'hover:bg-green-500 hover:text-white' : 'hover:bg-green-500 hover:text-white'; ?>">Admin Dashboard</a>
                </li>
            <?php } ?>
            <li><a href="logout.php" class="block py-2 px-4 rounded-lg transition duration-300 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'hover:bg-gray-700 hover:text-white' : 'hover:bg-white hover:text-primary'; ?>">Logout</a></li>
        </ul>
    </nav>

    <div class="mt-4">
        <button onclick="toggleTheme()" class="w-full p-2 rounded-lg flex items-center justify-center <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-700 text-gray-200' : 'bg-white text-primary'; ?>">
            <span id="themeIcon"><?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? '☀️' : '🌙'; ?></span>
            <span class="ml-2"><?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'Light Mode' : 'Dark Mode'; ?></span>
        </button>
    </div>
</aside>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('w-16');
        sidebar.classList.toggle('w-64');
    }
    function changeLanguage(lang) {
        alert('Language switched to: ' + lang);
    }
    function toggleTheme() {
        fetch('toggle_theme.php', { method: 'POST' })
            .then(() => {
                document.documentElement.classList.toggle('dark');
                const isDark = document.documentElement.classList.contains('dark');
                document.getElementById('themeIcon').textContent = isDark ? '☀️' : '🌙';
                document.querySelector('button[onclick="toggleTheme()"] span.ml-2').textContent = isDark ? 'Light Mode' : 'Dark Mode';
                document.body.className = isDark 
                    ? 'min-h-screen font-sans transition-colors duration-300 dark bg-gray-900 text-white'
                    : 'min-h-screen font-sans transition-colors duration-300 bg-gradient-to-br from-gray-100 to-gray-300 text-gray-900';
            });
    }
</script>