<?php
session_start();

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'hod'){
    header("Location: ../index.php");
    exit();
}

$page_title = "HOD Dashboard";

include '../includes/db.php';

$department = $_SESSION['department'] ?? '';

/* ================= COUNTERS ================= */

// TOTAL REQUESTS (ONLY HOD DEPARTMENT)
$total_requests = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total 
     FROM requests 
     WHERE department='$department'")
)['total'] ?? 0;

/* PENDING (waiting HOD approval) */
$pending = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total 
     FROM requests 
     WHERE department='$department' 
     AND status='pending'")
)['total'] ?? 0;

/* APPROVED BY HOD */
$approved = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total 
     FROM requests 
     WHERE department='$department' 
     AND status='hod_approved'")
)['total'] ?? 0;

/* REJECTED */
$rejected = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total 
     FROM requests 
     WHERE department='$department' 
     AND status='rejected'")
)['total'] ?? 0;
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<style>

.dashboard{
    margin-left:250px;
    padding:90px 20px;
    background:#f5f7fb;
    min-height:100vh;
}

.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    display:flex;
    align-items:center;
    justify-content:space-between;
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.icon{
    width:50px;
    height:50px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:20px;
}

.info h3{
    margin:0;
    font-size:24px;
}

.info p{
    margin:0;
    color:#777;
}

/* COLORS */
.total .icon{ background:#6b3f1d; }
.pending .icon{ background:#f59e0b; }
.approved .icon{ background:#10b981; }
.rejected .icon{ background:#ef4444; }

</style>

<div class="dashboard">

    <h2><i class="fas fa-user-tie"></i> HOD Dashboard</h2>
    <p>Welcome back, <?php echo $_SESSION['fullname']; ?> </p>

    <br>

    <div class="cards">

        <!-- TOTAL -->
        <div class="card total">
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="info">
                <h3><?php echo $total_requests; ?></h3>
                <p>Total Requests</p>
            </div>
        </div>

        <!-- PENDING -->
        <div class="card pending">
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="info">
                <h3><?php echo $pending; ?></h3>
                <p>Pending</p>
            </div>
        </div>

        <!-- APPROVED -->
        <div class="card approved">
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="info">
                <h3><?php echo $approved; ?></h3>
                <p>Approved</p>
            </div>
        </div>

        <!-- REJECTED -->
        <div class="card rejected">
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="info">
                <h3><?php echo $rejected; ?></h3>
                <p>Rejected</p>
            </div>
        </div>

    </div>

</div>