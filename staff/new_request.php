<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'staff') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['id'];
$page_title = "New Request";

/* FETCH USER DETAILS */
$user_query = mysqli_query($conn, "SELECT fullname, department FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($user_query);

$fullname = $user['fullname'];
$user_department = $user['department'];

/* SUBMIT REQUEST */
if (isset($_POST['submit'])) {

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);

    $sql = "INSERT INTO requests
            (
                user_id,
                department,
                title,
                description,
                amount,
                priority,
                status,
                created_at
            )
            VALUES
            (
                '$user_id',
                '$user_department',
                '$title',
                '$description',
                '$amount',
                '$priority',
                'pending',
                NOW()
            )";

    if (mysqli_query($conn, $sql)) {
        $success = "Request submitted successfully!";
    } else {
        $error = "Failed to submit request! " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>New Request</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

body{
    margin:0;
    font-family:Arial, sans-serif;
    background:#f4f0ff;
}

/* MAIN CONTENT */
.main-content{
    margin-left:260px;
    margin-top:80px;
    padding:25px;
}

/* CARD */
.card{
    max-width:850px;
    background:#fff;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    border-left:6px solid #7c3aed;
}

/* TITLE */
.card h2{
    color:#7c3aed;
    margin-bottom:25px;
}

/* LABEL */
label{
    font-weight:600;
    display:block;
    margin-bottom:5px;
}

/* INPUTS */
input,
textarea,
select{
    width:100%;
    padding:12px;
    margin-bottom:18px;
    border:1px solid #ddd;
    border-radius:10px;
    font-size:14px;
    box-sizing:border-box;
}

input:focus,
textarea:focus,
select:focus{
    outline:none;
    border-color:#7c3aed;
}

/* TEXTAREA */
textarea{
    resize:vertical;
}

/* BUTTON */
button{
    background:#7c3aed;
    color:white;
    border:none;
    padding:13px 20px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
    transition:0.3s;
}

button:hover{
    background:#5b21b6;
}

/* MESSAGE */
.msg{
    padding:12px;
    border-radius:10px;
    margin-bottom:15px;
    color:#fff;
}

.success{
    background:#16a34a;
}

.error{
    background:#dc2626;
}

/* READ ONLY */
.readonly{
    background:#f5f5f5;
}

</style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

<?php include 'includes/topbar.php'; ?>

<div class="card">

<h2>
    <i class="fas fa-file-signature"></i>
    New Request Letter
</h2>

<?php if(isset($success)){ ?>
<div class="msg success">
    <?php echo $success; ?>
</div>
<?php } ?>

<?php if(isset($error)){ ?>
<div class="msg error">
    <?php echo $error; ?>
</div>
<?php } ?>

<form method="POST">

    <!-- STAFF NAME -->
    <label>Staff Name</label>
    <input type="text"
           value="<?php echo htmlspecialchars($fullname); ?>"
           class="readonly"
           readonly>

    <!-- DEPARTMENT -->
    <label>Department</label>
    <input type="text"
           value="<?php echo htmlspecialchars($user_department); ?>"
           class="readonly"
           readonly>

    <!-- SUBJECT -->
    <label>Subject</label>
    <input type="text"
           name="title"
           placeholder="Enter request subject"
           required>

    <!-- PROFESSIONAL LETTER -->
    <label>Request Letter</label>

<textarea name="description" rows="15" required>Date: <?php echo date('d/m/Y'); ?>


THROUGH:
Head of Department

FROM:
<?php echo $fullname; ?>

DEPARTMENT:
<?php echo $user_department; ?>


SUBJECT: REQUEST FOR APPROVAL


Dear Sir/Madam,

I respectfully submit this request for your consideration and approval.

Purpose of Request:
.....................

......................

Reason / Justification:
........................

.......................

Expected Benefits:
..................

..................

Required Items / Services:
1
2
3 



I kindly request your favorable consideration and approval.

Thank you for your time and support.

Yours faithfully,


<?php echo $fullname; ?>
Staff Member
</textarea>

    <!-- AMOUNT -->
    <label>Estimated Amount (TZS)</label>
    <input type="number"
           name="amount"
           min="0"
           step="0.01"
           placeholder="Enter estimated amount"
           required>

    <!-- PRIORITY -->
    <label>Priority</label>
    <select name="priority" required>
        <option value="low">Low</option>
        <option value="medium" selected>Medium</option>
        <option value="high">High</option>
    </select>

    <button type="submit" name="submit">
        <i class="fas fa-paper-plane"></i>
        Submit Request
    </button>

</form>

</div>

</div>

</body>
</html>