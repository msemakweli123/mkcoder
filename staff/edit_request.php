<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'staff') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['id'];

if (!isset($_GET['id'])) {
    die("Invalid request ID");
}

$request_id = intval($_GET['id']);

$query = mysqli_query(
    $conn,
    "SELECT * FROM requests WHERE id='$request_id' AND user_id='$user_id'"
);

if (mysqli_num_rows($query) == 0) {
    die("Request not found or not allowed");
}

$data = mysqli_fetch_assoc($query);

if ($data['status'] != 'pending') {
    die("You cannot edit this request anymore (already processed).");
}

if (isset($_POST['update'])) {

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);

    $update = mysqli_query(
        $conn,
        "UPDATE requests SET 
            title='$title',
            description='$description',
            amount='$amount',
            priority='$priority',
            updated_at=NOW()
         WHERE id='$request_id' AND user_id='$user_id'"
    );

    if ($update) {
        header("Location: my_requests.php?msg=updated");
        exit();
    } else {
        $error = "Failed to update request";
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f4f6fb; /* WHITE MILK */
    color:#111;
}

.main-content{
    margin-left:260px;
    margin-top:80px;
    padding:20px;
}

.card{
    background:#ffffff;
    padding:25px;
    border-radius:16px;
    max-width:650px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    border-left:5px solid #7c3aed;
}

h2{
    color:#7c3aed;
    margin-bottom:15px;
}

label{
    font-weight:bold;
    display:block;
    margin-top:10px;
}

input, textarea, select{
    width:100%;
    padding:12px;
    margin-top:6px;
    margin-bottom:15px;
    border-radius:10px;
    border:1px solid #ddd;
    outline:none;
}

input:focus, textarea:focus, select:focus{
    border-color:#7c3aed;
}

button{
    background:#7c3aed;
    color:white;
    padding:12px 15px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
    transition:0.2s;
}

button:hover{
    background:#5b21b6;
}

.msg{
    padding:10px;
    border-radius:8px;
    background:#ef4444;
    color:white;
    margin-bottom:10px;
}
</style>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

    <?php include 'includes/topbar.php'; ?>

    <div class="card">

        <h2><i class="fas fa-pen-to-square"></i> Edit Request</h2>

        <?php if(isset($error)) echo "<div class='msg'><i class='fas fa-circle-exclamation'></i> $error</div>"; ?>

        <form method="POST">

            <label><i class="fas fa-heading"></i> Title</label>
            <input type="text" name="title" value="<?php echo $data['title']; ?>" required>

            <label><i class="fas fa-align-left"></i> Description</label>
            <textarea name="description" rows="4" required><?php echo $data['description']; ?></textarea>

            <label><i class="fas fa-coins"></i> Amount</label>
            <input type="number" name="amount" value="<?php echo $data['amount']; ?>" required>

            <label><i class="fas fa-flag"></i> Priority</label>
            <select name="priority" required>
                <option value="low" <?php if($data['priority']=='low') echo 'selected'; ?>>Low</option>
                <option value="medium" <?php if($data['priority']=='medium') echo 'selected'; ?>>Medium</option>
                <option value="high" <?php if($data['priority']=='high') echo 'selected'; ?>>High</option>
            </select>

            <button type="submit" name="update">
                <i class="fas fa-save"></i> Update Request
            </button>

        </form>

    </div>

</div>