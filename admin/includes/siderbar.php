<!-- SIDEBAR -->
<div class="sidebar">

    <div class="sidebar-header">

        <h3>
            <i class="fas fa-university"></i>
            UniProcure
        </h3>

        <p>
            <i class="fas fa-boxes-stacked"></i>
            Procurement System
        </p>

    </div>

    <div class="sidebar-menu">

        <a href="dashboard.php"
           class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">

            <span class="menu-icon"><i class="fas fa-chart-line"></i></span>
            <span>Dashboard</span>

        </a>

        <a href="add_user.php"
           class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'add_user.php' ? 'active' : ''; ?>">

            <span class="menu-icon"><i class="fas fa-user-plus"></i></span>
            <span>Add User</span>

        </a>

        <a href="manage_users.php"
           class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : ''; ?>">

            <span class="menu-icon"><i class="fas fa-users"></i></span>
            <span>Manage Users</span>

        </a>

    

    </div>

</div>