<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'dean') {
    header("Location: ../index.php");
    exit();
}

/* =========================
   FETCH APPROVED REQUESTS
   ========================= */
$query = mysqli_query($conn, "
    SELECT *
    FROM requests
    WHERE status = 'dean_approved'
    ORDER BY updated_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Dean - Approved Requests</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body{
    margin:0;
    font-family:Arial, sans-serif;
    background:#f4f6f9;
}

/* layout */
.container{
    margin-left:250px;
    padding:90px 20px;
}

/* card */
.card{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 6px 18px rgba(0,0,0,0.08);
}

/* title */
h2{
    margin:0 0 15px;
    color:#16a34a;
}

/* grid */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(260px, 1fr));
    gap:15px;
}

/* request card */
.req-card{
    background:#ffffff;
    border:1px solid #eee;
    border-radius:12px;
    padding:15px;
    cursor:pointer;
    transition:0.2s;
}

.req-card:hover{
    transform:translateY(-3px);
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}

.req-title{
    font-weight:bold;
    color:#111827;
    margin-bottom:6px;
}

.req-user{
    font-size:13px;
    color:#6b7280;
}

.req-status{
    margin-top:10px;
    display:inline-block;
    padding:4px 10px;
    border-radius:20px;
    font-size:11px;
    background:#16a34a;
    color:white;
}

/* modal */
.modal{
    display:none;
    position:fixed;
    top:0;left:0;
    width:100%;height:100%;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
}

.modal-content{
    background:#fff;
    width:600px;
    max-width:95%;
    border-radius:12px;
    padding:20px;
    animation:fadeIn 0.2s ease-in-out;
    max-height:85vh;
    overflow-y:auto;
}

@keyframes fadeIn{
    from{transform:scale(0.95);opacity:0;}
    to{transform:scale(1);opacity:1;}
}

.close{
    float:right;
    font-size:20px;
    cursor:pointer;
}

.label{
    font-weight:bold;
    color:#111827;
}
</style>

</head>

<body>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<div class="container">
<div class="card">

<h2><i class="fas fa-check-circle"></i> Dean Approved Requests</h2>

<div class="grid">

<?php while($row = mysqli_fetch_assoc($query)) { ?>

    <?php
        $title = htmlspecialchars($row['title'] ?? '');
        $user  = htmlspecialchars($row['user_id'] ?? 'Unknown');
        $desc  = htmlspecialchars($row['description'] ?? '');
        $hod   = htmlspecialchars($row['hod_comment'] ?? '-');
        $dean  = htmlspecialchars($row['dean_comment'] ?? '-');
        $date  = !empty($row['updated_at']) ? date('d M Y', strtotime($row['updated_at'])) : '-';
    ?>

    <div class="req-card"
        onclick="openLetter(
            `<?php echo $title; ?>`,
            `<?php echo $desc; ?>`,
            `<?php echo $user; ?>`,
            `<?php echo $hod; ?>`,
            `<?php echo $dean; ?>`,
            `<?php echo $date; ?>`
        )">

        <div class="req-title"><?php echo $title; ?></div>

        <div class="req-user">
            <i class="fas fa-user"></i> Staff: <?php echo $user; ?>
        </div>

        <div class="req-status">
            APPROVED
        </div>

    </div>

<?php } ?>

</div>

</div>
</div>

<!-- MODAL -->
<div id="modal" class="modal">
<div class="modal-content">

<span class="close" onclick="closeModal()">&times;</span>

<h3 id="m_title"></h3>

<p><span class="label">Staff:</span> <span id="m_user"></span></p>
<p><span class="label">Description:</span> <span id="m_desc"></span></p>

<hr>

<p><span class="label">HOD Comment:</span></p>
<p id="m_hod"></p>

<p><span class="label">Dean Comment:</span></p>
<p id="m_dean"></p>

<p><span class="label">Approved Date:</span> <span id="m_date"></span></p>

</div>
</div>

<script>

function openLetter(title, desc, user, hod, dean, date){
    document.getElementById('modal').style.display = 'flex';

    document.getElementById('m_title').innerText = title;
    document.getElementById('m_user').innerText = user;
    document.getElementById('m_desc').innerText = desc;
    document.getElementById('m_hod').innerText = hod;
    document.getElementById('m_dean').innerText = dean;
    document.getElementById('m_date').innerText = date;
}

function closeModal(){
    document.getElementById('modal').style.display = 'none';
}

window.onclick = function(e){
    if(e.target.classList.contains('modal')){
        closeModal();
    }
}
</script>

</body>
</html>