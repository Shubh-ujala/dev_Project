<footer class="p-6 mt-8 transition-colors duration-300 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'bg-gray-800 text-gray-200' : 'bg-gradient-to-r from-primary to-secondary text-white'; ?>">
    <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
        <p class="text-sm">© 2025 Grievance System</p>
        <div class="mt-4 md:mt-0">
            <a href="#" class="mx-3 transition duration-300 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'text-gray-400 hover:text-white' : 'text-gray-200 hover:text-white'; ?>">Contact Us</a>
            <a href="#" class="mx-3 transition duration-300 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'text-gray-400 hover:text-white' : 'text-gray-200 hover:text-white'; ?>">Privacy Policy</a>
            <a href="#" class="mx-3 transition duration-300 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'text-gray-400 hover:text-white' : 'text-gray-200 hover:text-white'; ?>">Terms of Service</a>
            <a href="#" class="mx-3 transition duration-300 <?php echo isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark' ? 'text-gray-400 hover:text-white' : 'text-gray-200 hover:text-white'; ?>">About Us</a>
        </div>
    </div>
</footer>
</body>
</html>