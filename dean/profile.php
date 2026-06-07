<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db.php';

$user_id = $_SESSION['id'] ?? 0;

/* GET USER */
$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id' LIMIT 1");
$user = mysqli_fetch_assoc($query);

$name = $user['fullname'] ?? 'Dean';
$email = $user['email'] ?? 'Not set';
$role = $user['role'] ?? 'dean';

/* SAFE IMAGE */
$profile_img = !empty($user['profile_image'])
    ? $user['profile_image']
    : null;
?>

<!DOCTYPE html>
<html>
<head>
<title>Dean Profile</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f4f6f9;
}

/* LAYOUT */
.container{
    margin-left:250px;
    padding:100px 20px;
    display:flex;
    justify-content:center;
}

/* CARD */
.card{
    background:white;
    width:420px;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
    overflow:hidden;
    text-align:center;
}

/* HEADER */
.card-header{
    background:#2c3e50;
    padding:25px;
    color:white;
}

/* PROFILE IMAGE */
.card-header img{
    width:90px;
    height:90px;
    border-radius:50%;
    border:3px solid white;
    object-fit:cover;
    margin-bottom:10px;
}

.card-header i{
    font-size:90px;
    color:#fff;
}

/* BODY */
.card-body{
    padding:20px;
}

.info{
    margin:10px 0;
    font-size:14px;
    color:#555;
}

.label{
    font-weight:bold;
    color:#333;
}

/* ROLE BADGE */
.badge{
    display:inline-block;
    padding:5px 10px;
    background:#3498db;
    color:white;
    border-radius:20px;
    font-size:12px;
    margin-top:5px;
}

/* BUTTON */
.btn{
    margin-top:15px;
    display:inline-block;
    padding:8px 15px;
    background:#27ae60;
    color:white;
    text-decoration:none;
    border-radius:5px;
    font-size:14px;
}

.btn:hover{
    background:#1e8449;
}
</style>

</head>

<body>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<div class="container">

    <div class="card">

        <!-- HEADER -->
        <div class="card-header">

            <?php if($profile_img): ?>
                <img src="<?php echo htmlspecialchars($profile_img); ?>">
            <?php else: ?>
                <i class="fas fa-user-circle"></i>
            <?php endif; ?>

            <h3><?php echo htmlspecialchars($name); ?></h3>

            <div class="badge"><?php echo strtoupper($role); ?></div>
        </div>

        <!-- BODY -->
        <div class="card-body">

            <div class="info">
                <span class="label"><i class="fas fa-envelope"></i> Email:</span><br>
                <?php echo htmlspecialchars($email); ?>
            </div>

            <div class="info">
                <span class="label"><i class="fas fa-user-shield"></i> Role:</span><br>
                <?php echo htmlspecialchars($role); ?>
            </div>

            <a href="edit_profile.php" class="btn">
                <i class="fas fa-edit"></i> Edit Profile
            </a>

        </div>

    </div>

</div>

</body>
</html>