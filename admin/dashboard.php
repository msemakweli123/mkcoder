<?php
session_start();

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit();
}

include '../includes/db.php';

/* PAGE TITLE */
$page_title = "Dashboard";

/* INCLUDE HEADER */
include 'includes/header.php';

/* INCLUDE SIDEBAR */
include 'includes/siderbar.php';

/* GET STATISTICS */
$total_users = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as count FROM users")
)['count'];

$total_staff = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='staff'")
)['count'];

$total_hod = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='hod'")
)['count'];

$total_dean = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='dean'")
)['count'];
?>

<!-- FONT AWESOME -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- MAIN CONTENT -->
<div class="main-content">

    <!-- INCLUDE TOPBAR -->
    <?php include 'includes/topbar.php'; ?>

    <!-- CONTENT -->
    <div class="content">

        <!-- STATS GRID -->
        <div class="stats-grid">

            <!-- TOTAL USERS -->
            <div class="stat-card">

                <div class="stat-info">

                    <h3><?php echo $total_users; ?></h3>

                    <p>Total Users</p>

                </div>

                <div class="stat-icon">

                    <i class="fas fa-users"></i>

                </div>

            </div>

            <!-- STAFF -->
            <div class="stat-card">

                <div class="stat-info">

                    <h3><?php echo $total_staff; ?></h3>

                    <p>Staff Members</p>

                </div>

                <div class="stat-icon">

                    <i class="fas fa-user-tie"></i>

                </div>

            </div>

            <!-- HOD -->
            <div class="stat-card">

                <div class="stat-info">

                    <h3><?php echo $total_hod; ?></h3>

                    <p>HODs</p>

                </div>

                <div class="stat-icon">

                    <i class="fas fa-chalkboard-teacher"></i>

                </div>

            </div>

            <!-- DEAN -->
            <div class="stat-card">

                <div class="stat-info">

                    <h3><?php echo $total_dean; ?></h3>

                    <p>Deans</p>

                </div>

                <div class="stat-icon">

                    <i class="fas fa-graduation-cap"></i>

                </div>

            </div>

        </div>

        <!-- RECENT USERS -->
        <h3 class="section-title">

            <i class="fas fa-clock-rotate-left"></i>

            Recent Users

        </h3>

        <div class="data-table">

            <table>

                <thead>

                    <tr>

                        <th>
                            <i class="fas fa-id-badge"></i>
                            ID
                        </th>

                        <th>
                            <i class="fas fa-user"></i>
                            Full Name
                        </th>

                        <th>
                            <i class="fas fa-envelope"></i>
                            Email
                        </th>

                        <th>
                            <i class="fas fa-user-tag"></i>
                            Role
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $result = mysqli_query(
                        $conn,
                        "SELECT * FROM users ORDER BY id DESC LIMIT 5"
                    );

                    while($row = mysqli_fetch_assoc($result)){

                        $role_class = "role-".$row['role'];

                        echo "

                        <tr>

                            <td>{$row['id']}</td>

                            <td>
                                <i class='fas fa-user-circle'></i>
                                {$row['fullname']}
                            </td>

                            <td>
                                <i class='fas fa-envelope'></i>
                                {$row['email']}
                            </td>

                            <td>
                                <span class='role-badge $role_class'>
                                    <i class='fas fa-user-tag'></i>
                                    {$row['role']}
                                </span>
                            </td>

                        </tr>

                        ";
                    }

                    ?>

                </tbody>

            </table>

        </div>

    </div>

    <!-- FOOTER -->
    <?php include 'includes/footer.php'; ?>

</div>