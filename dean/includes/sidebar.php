<?php
// dean_sidebar.php
?>

<style>
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 250px;
    height: 100%;
    background: #2c3e50;
    color: #fff;
    padding-top: 20px;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 18px;
}

.sidebar a {
    display: block;
    color: #fff;
    padding: 12px 20px;
    text-decoration: none;
    font-size: 14px;
    border-left: 3px solid transparent;
}

.sidebar a:hover {
    background: #34495e;
    border-left: 3px solid #1abc9c;
}

.sidebar i {
    margin-right: 10px;
}
</style>

<div class="sidebar">

    <h2>🎓 DEAN PANEL</h2>

    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>

    <a href="department_requests.php"><i class="fas fa-inbox"></i> Department Requests</a>

    <a href="approved_requests.php"><i class="fas fa-check-circle"></i> Approved Requests</a>

    <a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a>

    <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>

</div>