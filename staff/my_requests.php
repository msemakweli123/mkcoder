<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'staff') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM requests WHERE user_id='$user_id' ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Requests</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body{
            margin:0;
            font-family:Arial;
            background:#f4f6fb;
        }

        .main-content{
            margin-left:260px;
            padding:90px 25px 25px;
        }

        .card{
            background:#fff;
            padding:25px;
            border-radius:15px;
            box-shadow:0 10px 25px rgba(0,0,0,0.08);
        }

        h2{
            color:#6d28d9;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th{
            background:#6d28d9;
            color:white;
            padding:12px;
            text-align:left;
        }

        td{
            padding:12px;
            border-bottom:1px solid #eee;
            font-size:14px;
            cursor:pointer;
        }

        tr:hover{
            background:#f8f6ff;
        }

        .badge{
            padding:5px 10px;
            border-radius:20px;
            color:#fff;
            font-size:12px;
        }

        .pending{ background:#f59e0b; }
        .approved{ background:#16a34a; }
        .rejected{ background:#dc2626; }

        .edit-btn{
            background:#6d28d9;
            color:white;
            padding:6px 10px;
            border-radius:6px;
            text-decoration:none;
            font-size:12px;
            display:inline-block;
        }

        .edit-btn:hover{
            background:#4c1d95;
        }

        /* MODAL BACKDROP */
        .modal{
            display:none;
            position:fixed;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background:rgba(0,0,0,0.5);
            justify-content:center;
            align-items:flex-start;
            overflow-y:auto;
            padding:40px 0;
        }

        /* LETTER STYLE CARD */
        .modal-content{
            background:white;
            width:650px;
            max-width:95%;
            padding:30px;
            border-radius:12px;
            position:relative;

            max-height:90vh;
            overflow-y:auto;

            text-align:left;
            line-height:1.7;
        }

        .close{
            position:absolute;
            right:15px;
            top:10px;
            font-size:20px;
            cursor:pointer;
        }

        .label{
            font-weight:bold;
            color:#333;
        }

        .section{
            margin-bottom:15px;
            padding-bottom:10px;
            border-bottom:1px solid #eee;
        }

        #modal p{
            margin:5px 0;
            color:#333;
            font-size:14px;
        }

    </style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

<?php include 'includes/topbar.php'; ?>

<div class="card">

<h2><i class="fas fa-list"></i> My Requests</h2>

<table>

    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>

    <?php while($row = mysqli_fetch_assoc($query)) { 

        $status = $row['status'] ?: "pending";

    ?>

        <tr onclick="openModal(
            '<?php echo addslashes($row['title']); ?>',
            `<?php echo addslashes($row['description']); ?>`,
            '<?php echo $status; ?>',
            `<?php echo addslashes($row['hod_comment']); ?>`,
            `<?php echo addslashes($row['dean_comment']); ?>`,
            '<?php echo date('d M Y', strtotime($row['created_at'])); ?>'
        )">

            <td>#<?php echo $row['id']; ?></td>

            <td>
                <i class="fas fa-file-alt"></i>
                <?php echo htmlspecialchars($row['title']); ?>
            </td>

            <td>
                <span class="badge <?php echo $status; ?>">
                    <?php echo ucfirst($status); ?>
                </span>
            </td>

            <td>
                <?php echo date('d M Y', strtotime($row['created_at'])); ?>
            </td>

            <td onclick="event.stopPropagation();">
                <a href="edit_request.php?id=<?php echo $row['id']; ?>" class="edit-btn">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </td>

        </tr>

    <?php } ?>

    </tbody>

</table>

</div>
</div>

<!-- MODAL -->
<div class="modal" id="modal">
    <div class="modal-content">

        <span class="close" onclick="closeModal()">&times;</span>

        <h3 id="m_title"></h3>

        <div class="section">
            <div class="label">Description:</div>
            <p id="m_desc"></p>
        </div>

        <div class="section">
            <div class="label">Status:</div>
            <p id="m_status"></p>
        </div>

        <div class="section">
            <div class="label">HOD Comment:</div>
            <p id="m_hod"></p>
        </div>

        <div class="section">
            <div class="label">Dean Comment:</div>
            <p id="m_dean"></p>
        </div>

        <div class="section">
            <div class="label">Date:</div>
            <p id="m_date"></p>
        </div>

    </div>
</div>

<script>

function openModal(title, desc, status, hod, dean, date){
    document.getElementById('modal').style.display = 'flex';

    document.getElementById('m_title').innerText = title;
    document.getElementById('m_desc').innerText = desc;
    document.getElementById('m_status').innerText = status;
    document.getElementById('m_hod').innerText = hod;
    document.getElementById('m_dean').innerText = dean;
    document.getElementById('m_date').innerText = date;
}

function closeModal(){
    document.getElementById('modal').style.display = 'none';
}

window.onclick = function(event){
    let modal = document.getElementById('modal');
    if(event.target == modal){
        modal.style.display = "none";
    }
}

</script>

</body>
</html>