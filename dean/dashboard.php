<?php
session_start();

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'dean'){
    header("Location: ../index.php");
    exit();
}

include '../includes/db.php';

$page_title = "Dean Dashboard";
$dean_name = $_SESSION['fullname'];

/* COUNTERS */
$total_pending = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total FROM requests WHERE status='hod_approved'"
))['total'] ?? 0;

$total_approved = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total FROM requests WHERE status='dean_approved'"
))['total'] ?? 0;

$total_rejected = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total FROM requests WHERE status='rejected'"
))['total'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>

<title>Dean Dashboard</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

body{
    margin:0;
    font-family:Arial;
    background:#f4f6f9;
}

/* MAIN */
.main-content{
    margin-left:250px;
    padding:90px 20px;
}

/* TOP HEADER CARD */
.header-card{
    background:white;
    padding:20px;
    border-radius:12px;
    margin-bottom:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header-card h2{
    color:#6b3f1d;
}

/* CARDS */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:15px;
}

.card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
}

.card h3{
    margin:0;
    color:#555;
}

.card .number{
    font-size:28px;
    font-weight:bold;
    margin-top:10px;
}

.pending{color:orange;}
.approved{color:green;}
.rejected{color:red;}

/* QUICK ACTIONS */
.actions{
    margin-top:20px;
    background:white;
    padding:20px;
    border-radius:12px;
}

.btn{
    display:inline-block;
    padding:10px 15px;
    border-radius:8px;
    text-decoration:none;
    color:white;
    margin-right:10px;
}

.btn-view{
    background:#3498db;
}

.btn-view:hover{
    background:#1d6fa5;
}

.btn-approve{
    background:#27ae60;
}

.btn-approve:hover{
    background:#1e8449;
}

</style>

</head>

<body>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<div class="main-content">

    <!-- HEADER -->
    <div class="header-card">
        <h2> Dean Control Panel</h2>
        <span>Welcome, <?php echo $dean_name; ?></span>
    </div>

    <!-- STATS -->
    <div class="cards">

        <div class="card">
            <h3><i class="fas fa-clock"></i> Pending Review</h3>
            <div class="number pending"><?php echo $total_pending; ?></div>
        </div>

        <div class="card">
            <h3><i class="fas fa-check-circle"></i> Approved</h3>
            <div class="number approved"><?php echo $total_approved; ?></div>
        </div>

        <div class="card">
            <h3><i class="fas fa-times-circle"></i> Rejected</h3>
            <div class="number rejected"><?php echo $total_rejected; ?></div>
        </div>

    </div>

    <!-- QUICK ACTIONS -->
    <div class="actions">

        <h3> Quick Actions</h3>

        <br>

        <a href="requests.php" class="btn btn-view">
            <i class="fas fa-list"></i> View Requests
        </a>

        <a href="pending.php" class="btn btn-approve">
            <i class="fas fa-hourglass"></i> Pending Approvals
        </a>

    </div>

</div>

</body>
</html>