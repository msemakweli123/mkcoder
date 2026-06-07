<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db.php';

/* =========================
   FILTERS
   ========================= */
$status = $_GET['status'] ?? '';
$department = $_GET['department'] ?? '';

$where = "WHERE 1=1";

if (!empty($status)) {
    $status = mysqli_real_escape_string($conn, $status);
    $where .= " AND status='$status'";
}

if (!empty($department)) {
    $department = mysqli_real_escape_string($conn, $department);
    $where .= " AND department='$department'";
}

/* =========================
   STATS
   ========================= */
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM requests"))['t'] ?? 0;

$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM requests WHERE status='pending'"))['t'] ?? 0;

$hod = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM requests WHERE status='hod_approved'"))['t'] ?? 0;

$dean = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM requests WHERE status='dean_approved'"))['t'] ?? 0;

$rejected = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM requests WHERE status='rejected'"))['t'] ?? 0;

/* =========================
   DATA
   ========================= */
$query = mysqli_query($conn, "
    SELECT *
    FROM requests
    $where
    ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Reports</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f4f6f9;
}

.container{
    margin-left:250px;
    padding:90px 20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

/* TITLE */
h2{
    color:#2c3e50;
    margin-bottom:10px;
}

/* STATS */
.stats{
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:10px;
    margin-bottom:15px;
}

.box{
    padding:15px;
    border-radius:10px;
    color:white;
    text-align:center;
}

.total{background:#2c3e50;}
.pending{background:orange;}
.hod{background:#3498db;}
.dean{background:green;}
.rejected{background:red;}

/* FILTER */
.filter{
    display:flex;
    gap:10px;
    margin-bottom:15px;
}

select{
    padding:8px;
    border-radius:5px;
    border:1px solid #ddd;
}

/* TABLE */
.table-wrap{
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
    min-width:1100px;
}

th{
    background:#2c3e50;
    color:white;
    padding:10px;
    font-size:13px;
    text-align:left;
}

td{
    padding:10px;
    border-bottom:1px solid #eee;
    font-size:13px;
}

/* BADGES */
.badge{
    padding:4px 8px;
    border-radius:5px;
    color:white;
    font-size:11px;
}

.pending{background:orange;}
.hod_approved{background:#3498db;}
.dean_approved{background:green;}
.rejected{background:red;}
</style>

</head>

<body>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<div class="container">

<div class="card">

<h2><i class="fas fa-chart-line"></i> System Reports</h2>

<!-- STATS -->
<div class="stats">

    <div class="box total">
        <h3><?php echo $total; ?></h3>
        <p>Total Requests</p>
    </div>

    <div class="box pending">
        <h3><?php echo $pending; ?></h3>
        <p>Pending</p>
    </div>

    <div class="box hod">
        <h3><?php echo $hod; ?></h3>
        <p>HOD Approved</p>
    </div>

    <div class="box dean">
        <h3><?php echo $dean; ?></h3>
        <p>Dean Approved</p>
    </div>

    <div class="box rejected">
        <h3><?php echo $rejected; ?></h3>
        <p>Rejected</p>
    </div>

</div>

<!-- FILTER -->
<form method="GET" class="filter">

    <select name="status">
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="hod_approved">HOD Approved</option>
        <option value="dean_approved">Dean Approved</option>
        <option value="rejected">Rejected</option>
    </select>

    <button class="box total" style="border:none;cursor:pointer;">
        Filter
    </button>

</form>

<!-- TABLE -->
<div class="table-wrap">

<table>

<thead>
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Item</th>
    <th>Qty</th>
    <th>Amount</th>
    <th>Status</th>
    <th>Department</th>
    <th>Date</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($query)) { ?>

<tr>

    <td>#<?php echo $row['id']; ?></td>
    <td><?php echo htmlspecialchars($row['title']); ?></td>
    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
    <td><?php echo (int)$row['quantity']; ?></td>
    <td>TZS <?php echo number_format($row['amount']); ?></td>

    <td>
        <span class="badge <?php echo $row['status']; ?>">
            <?php echo strtoupper($row['status']); ?>
        </span>
    </td>

    <td><?php echo htmlspecialchars($row['department']); ?></td>

    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</body>
</html>