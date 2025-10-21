<div class="topbar">
    <div class="topbar-left">
        <h1><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h1>
    </div>
    
    <div class="topbar-right">
        <div class="user-menu">
            <div class="user-info">
                <span>Welcome, <?php echo $_SESSION['admin_name'] ?? 'Admin'; ?></span>
                <div class="user-dropdown">
                    <button class="dropdown-toggle" onclick="toggleUserDropdown()">
                        <svg class="icon"><use href="frontend/assets/svg/icons.svg#settings"></use></svg>
                    </button>
                    <div class="dropdown-menu" id="userDropdown">
                        <a href="profile.php">Profile</a>
                        <a href="settings.php">Settings</a>
                        <hr>
                        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('show');
}

// Close dropdown when clicking outside
window.onclick = function(event) {
    if (!event.target.matches('.dropdown-toggle')) {
        const dropdowns = document.getElementsByClassName('dropdown-menu');
        for (let i = 0; i < dropdowns.length; i++) {
            const openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}
</script>
