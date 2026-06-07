<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db.php';

/* =========================
   HANDLE DEAN ACTION + NOTIFICATION
   ========================= */
if (isset($_POST['dean_action'])) {

    $request_id = (int)($_POST['request_id'] ?? 0);

    // ✅ NOW TAKES REAL INPUT FROM TEXTAREA
    $dean_comment = mysqli_real_escape_string($conn, $_POST['dean_comment'] ?? '');

    $dean_status = $_POST['dean_status'] ?? '';

    if ($dean_status === "approve") {
        $status = "dean_approved";
        $message = "Your request #$request_id has been APPROVED by Dean";
    } elseif ($dean_status === "reject") {
        $status = "rejected";
        $message = "Your request #$request_id has been REJECTED by Dean";
    } else {
        $status = "pending";
        $message = "";
    }

    if ($request_id > 0) {

        /* UPDATE REQUEST */
        mysqli_query($conn, "
            UPDATE requests SET
                status='$status',
                dean_comment='$dean_comment',
                updated_at=NOW()
            WHERE id=$request_id
        ");

        /* GET USER */
        $u = mysqli_query($conn, "SELECT user_id FROM requests WHERE id=$request_id");
        $user = mysqli_fetch_assoc($u);
        $user_id = $user['user_id'] ?? 0;

        /* INSERT NOTIFICATION */
        if ($user_id && $message != "") {
            mysqli_query($conn, "
                INSERT INTO notifications (user_id, message, is_read, created_at)
                VALUES ('$user_id', '$message', 0, NOW())
            ");
        }
    }
}

/* FETCH */
$query = mysqli_query($conn, "
    SELECT * FROM requests
    ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Dean Requests</title>

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
}

td{
    padding:10px;
    border-bottom:1px solid #eee;
    font-size:13px;
    vertical-align:top;
}

/* STATUS */
.badge{
    padding:5px 10px;
    border-radius:6px;
    color:white;
    font-size:11px;
    display:inline-block;
    text-transform:capitalize;
}

.status-pending{ background:orange; }
.status-hod_approved{ background:#3498db; }
.status-dean_approved{ background:green; }
.status-rejected{ background:red; }

/* BUTTONS */
.btn{
    padding:5px 8px;
    border:none;
    border-radius:5px;
    cursor:pointer;
    font-size:12px;
    margin:2px;
}

.approve{ background:green; color:white; }
.reject{ background:red; color:white; }
.view{ background:#3498db; color:white; }

.btn:hover{ opacity:0.85; }

/* TEXTAREA (NEW FIX) */
textarea{
    width:160px;
    height:60px;
    padding:5px;
    font-size:12px;
    border:1px solid #ccc;
    border-radius:5px;
    resize:none;
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
    padding:20px;
    border-radius:12px;
    max-height:85vh;
    overflow-y:auto;
}

.close{
    float:right;
    cursor:pointer;
    font-size:20px;
}
</style>

</head>

<body>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<div class="container">

<div class="card">

<h2>📩 Dean Requests</h2>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Status</th>
    <th>HOD Comment</th>
    <th>Dean Comment</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($query)) { ?>

<?php 
$status = $row['status'] ?? 'pending';
$statusClass = "status-" . $status;
?>

<tr>

    <td>#<?php echo $row['id']; ?></td>

    <td><?php echo htmlspecialchars($row['title']); ?></td>

    <td>
        <span class="badge <?php echo $statusClass; ?>">
            <?php echo strtoupper(str_replace('_',' ',$status)); ?>
        </span>
    </td>

    <td><?php echo $row['hod_comment'] ?: '-'; ?></td>

    <td><?php echo $row['dean_comment'] ?: '-'; ?></td>

    <td>

        <!-- VIEW -->
        <button class="btn view"
            onclick="openLetter(
                '<?php echo $row['id']; ?>',
                '<?php echo addslashes($row['title']); ?>',
                `<?php echo addslashes($row['description']); ?>`,
                '<?php echo $row['item_name'] ?? '-'; ?>',
                '<?php echo $row['quantity'] ?? '-'; ?>',
                '<?php echo $row['amount'] ?? 0; ?>',
                '<?php echo $status; ?>',
                `<?php echo addslashes($row['hod_comment'] ?? '-'); ?>`,
                `<?php echo addslashes($row['dean_comment'] ?? '-'); ?>`
            )">
            <i class="fas fa-eye"></i> View
        </button>

        <!-- APPROVE -->
        <form method="POST" style="display:inline;">
            <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
            <input type="hidden" name="dean_action" value="1">
            <input type="hidden" name="dean_status" value="approve">

            <!-- REAL COMMENT INPUT -->
            <textarea name="dean_comment" placeholder="Write dean comment..."></textarea>

            <button class="btn approve">Approve</button>
        </form>

        <!-- REJECT -->
        <form method="POST" style="display:inline;">
            <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
            <input type="hidden" name="dean_action" value="1">
            <input type="hidden" name="dean_status" value="reject">

            <!-- REAL COMMENT INPUT -->
            <textarea name="dean_comment" placeholder="Write dean comment..."></textarea>

            <button class="btn reject">Reject</button>
        </form>

    </td>

</tr>

<?php } ?>

</tbody>
</table>

</div>
</div>

<!-- MODAL -->
<div id="modal" class="modal">
<div class="modal-content">

<span class="close" onclick="closeModal()">&times;</span>

<h2 id="l_title"></h2>

<p><b>ID:</b> <span id="l_id"></span></p>
<p><b>Item:</b> <span id="l_item"></span></p>
<p><b>Qty:</b> <span id="l_qty"></span></p>
<p><b>Amount:</b> <span id="l_amount"></span></p>

<hr>

<p><b>Description:</b></p>
<p id="l_desc"></p>

<hr>

<p><b>HOD Comment:</b></p>
<p id="l_hod"></p>

<p><b>Dean Comment:</b></p>
<p id="l_dean"></p>

<p><b>Status:</b> <span id="l_status"></span></p>

</div>
</div>

<script>

function openLetter(id,title,desc,item,qty,amount,status,hod,dean){
    document.getElementById('modal').style.display='flex';

    document.getElementById('l_id').innerText=id;
    document.getElementById('l_title').innerText=title;
    document.getElementById('l_desc').innerText=desc;
    document.getElementById('l_item').innerText=item;
    document.getElementById('l_qty').innerText=qty;
    document.getElementById('l_amount').innerText=amount;
    document.getElementById('l_status').innerText=status;
    document.getElementById('l_hod').innerText=hod;
    document.getElementById('l_dean').innerText=dean;
}

function closeModal(){
    document.getElementById('modal').style.display='none';
}

window.onclick=function(e){
    if(e.target==document.getElementById('modal')){
        closeModal();
    }
}
</script>

</body>
</html>