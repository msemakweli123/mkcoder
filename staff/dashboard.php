<?php
session_start();

/* AUTH CHECK FIRST (NO OUTPUT BEFORE THIS) */
if(!isset($_SESSION['id']) || $_SESSION['role'] != 'staff'){
    header("Location: ../index.php");
    exit();
}

include '../includes/db.php';

$user_id = (int)$_SESSION['id'];

/* COUNTS */
function getCount($conn, $sql){
    $result = mysqli_query($conn, $sql);
    if(!$result) return 0;
    $row = mysqli_fetch_assoc($result);
    return $row['count'] ?? 0;
}

$my_requests = getCount($conn,
    "SELECT COUNT(*) AS count FROM requests WHERE user_id=$user_id"
);

$pending = getCount($conn,
    "SELECT COUNT(*) AS count FROM requests 
     WHERE user_id=$user_id AND status='pending'"
);

$approved = getCount($conn,
    "SELECT COUNT(*) AS count FROM requests 
     WHERE user_id=$user_id AND status='approved'"
);

$page_title = "Dashboard";
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

body{
    margin:0;
    overflow-x:hidden;
    font-family:Arial, sans-serif;
    background:#f5f7fb;
}

/* MAIN LAYOUT FIX */
.dashboard-container{
    margin-left:260px;  /* PUSH CONTENT AWAY FROM SIDEBAR */
    padding-top:90px;   /* SPACE FOR TOPBAR */
    background:#f5f7fb;
    min-height:100vh;
}

/* WELCOME */
.welcome-box{
    background:linear-gradient(135deg,#8B4513,#b06d2d);
    color:white;
    padding:25px;
    border-radius:15px;
    margin-bottom:25px;
}

/* GRID */
.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}

/* CARD */
.stat-card{
    background:#fff;
    border-radius:15px;
    padding:20px;
    box-shadow:0 3px 12px rgba(0,0,0,.08);
}

.card-inner{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.stat-card h2{
    margin:0;
    font-size:32px;
}

.icon{
    width:55px;
    height:55px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
}

/* COLORS */
.requests .icon{ background:#8B4513; }
.pending .icon{ background:#f59e0b; }
.approved .icon{ background:#10b981; }

.requests{ border-left:5px solid #8B4513; }
.pending{ border-left:5px solid #f59e0b; }
.approved{ border-left:5px solid #10b981; }

/* RESPONSIVE */
@media(max-width:768px){
    .dashboard-container{
        margin-left:0;
    }
}

</style>

<!-- SIDEBAR -->
<?php include 'includes/sidebar.php'; ?>

<!-- TOPBAR -->
<?php include 'includes/topbar.php'; ?>

<!-- CONTENT -->
<div class="dashboard-container">

    <div class="welcome-box">
        <h3><i class="fas fa-user-circle"></i> Staff Dashboard</h3>

        <p>
            Welcome back,
            <?php echo htmlspecialchars($_SESSION['fullname'] ?? 'User'); ?>
        </p>
    </div>

    <div class="stats-grid">

        <div class="stat-card requests">
            <div class="card-inner">
                <div>
                    <h2><?php echo $my_requests; ?></h2>
                    <p>My Requests</p>
                </div>
                <div class="icon"><i class="fas fa-file-alt"></i></div>
            </div>
        </div>

        <div class="stat-card pending">
            <div class="card-inner">
                <div>
                    <h2><?php echo $pending; ?></h2>
                    <p>Pending</p>
                </div>
                <div class="icon"><i class="fas fa-hourglass-half"></i></div>
            </div>
        </div>

        <div class="stat-card approved">
            <div class="card-inner">
                <div>
                    <h2><?php echo $approved; ?></h2>
                    <p>Approved</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>

    </div>

</div>