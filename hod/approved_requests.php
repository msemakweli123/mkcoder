<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'hod') {
    header("Location: ../index.php");
    exit();
}

$dept = $_SESSION['department'] ?? '';

$sql = "SELECT * FROM requests 
        WHERE department='$dept'
        AND status='approved'
        ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Approved Requests</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f4f6f9;
}

.content{
    margin-left:250px;
    padding:90px 20px;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));
    gap:15px;
}

/* CARD */
.card{
    background:white;
    padding:18px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,0.06);
    cursor:pointer;
    transition:0.2s;
}

.card:hover{
    transform:translateY(-4px);
}

.name{
    font-weight:bold;
    color:#16a34a;
    margin-bottom:6px;
}

.dept{
    font-size:13px;
    color:#555;
}

.small{
    font-size:12px;
    color:#777;
    margin-top:8px;
}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
    justify-content:center;
    align-items:center;
}

.modal-content{
    background:white;
    width:600px;
    max-width:95%;
    padding:25px;
    border-radius:12px;
    max-height:85vh;
    overflow-y:auto;
}

.close{
    float:right;
    cursor:pointer;
    font-size:20px;
}

/* LETTER */
.letter h2{
    color:#16a34a;
    text-align:center;
}

.label{
    font-weight:bold;
    color:#111827;
}

.hr{
    border-top:1px solid #ddd;
    margin:12px 0;
}
</style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<div class="content">

<h2>✅ Approved Requests</h2>

<div class="grid">

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<div class="card"
onclick="openLetter(
    '<?php echo addslashes($row['title']); ?>',
    `<?php echo addslashes($row['description']); ?>`,
    '<?php echo $row['user_id']; ?>',
    '<?php echo addslashes($row['department']); ?>',
    `<?php echo addslashes($row['hod_comment'] ?? '-'); ?>`,
    `<?php echo addslashes($row['dean_comment'] ?? '-'); ?>`,
    '<?php echo date('d M Y', strtotime($row['created_at'])); ?>'
)">

    <div class="name">
        👤 Staff ID: <?php echo $row['user_id']; ?>
    </div>

    <div class="dept">
        🏢 Department: <?php echo $row['department']; ?>
    </div>

    <div class="small">
        📄 <?php echo substr($row['description'], 0, 60); ?>...
    </div>

</div>

<?php } ?>

</div>
</div>

<!-- LETTER MODAL -->
<div id="modal" class="modal">
<div class="modal-content letter">

<span class="close" onclick="closeModal()">&times;</span>

<h2>Official Approval Letter</h2>

<p><span class="label">Staff ID:</span> <span id="l_staff"></span></p>
<p><span class="label">Department:</span> <span id="l_dept"></span></p>

<div class="hr"></div>

<p><span class="label">Title:</span> <span id="l_title"></span></p>
<p><span class="label">Description:</span> <span id="l_desc"></span></p>
<p><span class="label">Date:</span> <span id="l_date"></span></p>

<div class="hr"></div>

<p><span class="label">HOD Comment:</span></p>
<p id="l_hod"></p>

<p><span class="label">Dean Comment:</span></p>
<p id="l_dean"></p>

</div>
</div>

<script>

function openLetter(title, desc, staff, dept, hod, dean, date){
    document.getElementById('modal').style.display = 'flex';

    document.getElementById('l_title').innerText = title;
    document.getElementById('l_desc').innerText = desc;
    document.getElementById('l_staff').innerText = staff;
    document.getElementById('l_dept').innerText = dept;
    document.getElementById('l_hod').innerText = hod || '-';
    document.getElementById('l_dean').innerText = dean || '-';
    document.getElementById('l_date').innerText = date;
}

function closeModal(){
    document.getElementById('modal').style.display = 'none';
}

window.onclick = function(e){
    if(e.target.classList.contains('modal')){
        e.target.style.display = 'none';
    }
}
</script>

</body>
</html>