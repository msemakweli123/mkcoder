<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'hod') {
    header("Location: ../index.php");
    exit();
}

$dept = $_SESSION['department'] ?? '';
$hod_name = $_SESSION['fullname'];

/* SAVE SIGNATURE ONLY */
if (isset($_POST['save_signature'])) {

    $request_id = $_POST['request_id'];
    $comment = mysqli_real_escape_string($conn, $_POST['hod_comment']);

    mysqli_query($conn,
        "UPDATE requests SET 
            hod_comment='$comment',
            hod_signature='$hod_name',
            updated_at=NOW()
         WHERE id='$request_id'
        "
    );

    header("Location: my_requests.php");
    exit();
}

/* FETCH REQUESTS */
$sql = "SELECT * FROM requests 
        WHERE department='$dept'
        AND status='pending'
        ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>HOD Requests</title>

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

.card{
    background:white;
    padding:20px;
    border-radius:10px;
}

table{
    width:100%;
    border-collapse:collapse;
    min-width:900px;
}

th{
    background:#6b3f1d;
    color:white;
    padding:12px;
    text-align:left;
}

td{
    padding:12px;
    border-bottom:1px solid #eee;
    vertical-align:top;
}

.badge{
    padding:5px 10px;
    border-radius:5px;
    color:white;
    font-size:12px;
}

.pending{background:orange;}
.approved{background:green;}
.rejected{background:red;}

.comment{
    max-width:250px;
    font-size:13px;
    color:#555;
}

/* BUTTONS */
.btn{
    padding:6px 10px;
    border:none;
    border-radius:5px;
    cursor:pointer;
    color:white;
    font-size:12px;
}

.btn-sign{ background:#27ae60; }
.btn-view{ background:#3498db; margin-right:5px; }

.btn-sign:hover{ background:#1e8449; }
.btn-view:hover{ background:#1d6fa5; }

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
    width:550px;
    max-width:95%;
    max-height:85vh;
    overflow-y:auto;
    padding:25px;
    border-radius:12px;
}

.close{
    float:right;
    cursor:pointer;
    font-size:20px;
}

.letter h3{
    margin-top:0;
    color:#6b3f1d;
    border-bottom:1px solid #eee;
    padding-bottom:10px;
}

.label{
    font-weight:bold;
}
</style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<div class="content">
<div class="card">

<h2>📋 Department Requests</h2>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Description</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>

    <td>#<?php echo $row['id']; ?></td>

    <td><?php echo htmlspecialchars($row['title']); ?></td>

    <!-- DESCRIPTION -->
    <td class="comment">
        <?php echo substr($row['description'], 0, 80); ?>...
    </td>

    <td>
        <button class="btn btn-view"
            onclick="openLetter(
                '<?php echo addslashes($row['title']); ?>',
                `<?php echo addslashes($row['description']); ?>`,
                '<?php echo $row['status']; ?>',
                `<?php echo addslashes($row['hod_comment'] ?? '-'); ?>`,
                `<?php echo addslashes($row['dean_comment'] ?? '-'); ?>`
            )">
            <i class="fas fa-eye"></i> View Letter
        </button>

        <button class="btn btn-sign"
            onclick="openModal(
                <?php echo $row['id']; ?>,
                `<?php echo addslashes($row['hod_comment'] ?? ''); ?>`
            )">
            <i class="fas fa-signature"></i> Sign
        </button>
    </td>

</tr>

<?php } ?>

</tbody>
</table>

</div>
</div>

<!-- SIGNATURE MODAL -->
<div id="modal" class="modal">
<div class="modal-content">

<span class="close" onclick="closeModal()">&times;</span>

<h3>HOD Signature</h3>

<form method="POST">

<input type="hidden" name="request_id" id="request_id">

<textarea name="hod_comment" id="hod_comment"
placeholder="Optional comment..."></textarea>

<br><br>

<button type="submit" name="save_signature" class="btn btn-sign">
    Save Signature
</button>

</form>

</div>
</div>

<!-- LETTER MODAL -->
<div id="letterModal" class="modal">
<div class="modal-content letter">

<span class="close" onclick="closeLetter()">&times;</span>

<h3 id="l_title"></h3>

<p><span class="label">Description:</span> <span id="l_desc"></span></p>
<p><span class="label">Status:</span> <span id="l_status"></span></p>

<hr>

<p><span class="label">HOD Comment:</span></p>
<p id="l_hod"></p>

<p><span class="label">Dean Comment:</span></p>
<p id="l_dean"></p>

</div>
</div>

<script>

function openModal(id, comment){
    document.getElementById('modal').style.display = 'flex';
    document.getElementById('request_id').value = id;
    document.getElementById('hod_comment').value = comment;
}

function closeModal(){
    document.getElementById('modal').style.display = 'none';
}

function openLetter(title, desc, status, hod, dean){
    document.getElementById('letterModal').style.display = 'flex';

    document.getElementById('l_title').innerText = title;
    document.getElementById('l_desc').innerText = desc;
    document.getElementById('l_status').innerText = status;
    document.getElementById('l_hod').innerText = hod;
    document.getElementById('l_dean').innerText = dean;
}

function closeLetter(){
    document.getElementById('letterModal').style.display = 'none';
}

window.onclick = function(e){
    if(e.target.classList.contains('modal')){
        e.target.style.display = 'none';
    }
}
</script>

</body>
</html>