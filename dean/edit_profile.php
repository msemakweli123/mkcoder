<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db.php';

$user_id = $_SESSION['id'] ?? 0;

/* FETCH USER */
$userQuery = mysqli_query($conn, "
    SELECT id, fullname, email, password, profile_image 
    FROM users 
    WHERE id='$user_id' 
    LIMIT 1
");

$user = mysqli_fetch_assoc($userQuery);

if (!$user) {
    die("User not found");
}

/* VALUES */
$fullname = $user['fullname'] ?? '';
$email = $user['email'] ?? '';
$profile_image = $user['profile_image'] ?? '';
$message = "";

/* =========================
   UPDATE PROFILE + PASSWORD
   ========================= */
if (isset($_POST['update_profile'])) {

    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $new_image = $profile_image;

    /* IMAGE UPLOAD */
    if (!empty($_FILES['profile_image']['name'])) {

        $targetDir = "../uploads/profiles/";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $targetFile = $targetDir . $fileName;

        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
                $new_image = $targetFile;
            }
        }
    }

    /* =========================
       PASSWORD CHANGE SECTION
       ========================= */
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $hashed_password = $user['password'];

    if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {

        if (!password_verify($current_password, $user['password'])) {
            $message = "❌ Current password is incorrect!";
        }
        elseif ($new_password !== $confirm_password) {
            $message = "❌ New passwords do not match!";
        }
        elseif (strlen($new_password) < 6) {
            $message = "❌ Password must be at least 6 characters!";
        }
        else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        }
    }

    /* UPDATE DB */
    if (empty($message)) {

        $update = mysqli_query($conn, "
            UPDATE users SET 
                fullname='$fullname',
                email='$email',
                password='$hashed_password',
                profile_image='$new_image'
            WHERE id='$user_id'
        ");

        if ($update) {
            $message = "✅ Profile updated successfully!";
        } else {
            $message = "❌ Update failed!";
        }
    }

    /* REFRESH */
    $userQuery = mysqli_query($conn, "
        SELECT id, fullname, email, password, profile_image 
        FROM users 
        WHERE id='$user_id' 
        LIMIT 1
    ");

    $user = mysqli_fetch_assoc($userQuery);

    $fullname = $user['fullname'];
    $email = $user['email'];
    $profile_image = $user['profile_image'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Profile</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f4f6f9;
}

.container{
    margin-left:250px;
    padding:100px 20px;
    display:flex;
    justify-content:center;
}

.card{
    background:white;
    width:450px;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
    padding:20px;
}

h2{
    text-align:center;
    color:#2c3e50;
}

input{
    width:100%;
    padding:10px;
    margin:8px 0;
    border:1px solid #ddd;
    border-radius:5px;
}

button{
    width:100%;
    padding:10px;
    background:#27ae60;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

button:hover{
    background:#1e8449;
}

.msg{
    text-align:center;
    margin-bottom:10px;
    color:green;
}
.profile-img{
    width:80px;
    height:80px;
    border-radius:50%;
    object-fit:cover;
    display:block;
    margin:0 auto;
}
.section-title{
    margin-top:15px;
    font-weight:bold;
    color:#333;
}
</style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<div class="container">

<div class="card">

<h2><i class="fas fa-user-edit"></i> Edit Profile</h2>

<?php if($message): ?>
<div class="msg"><?php echo $message; ?></div>
<?php endif; ?>

<!-- IMAGE -->
<?php if(!empty($profile_image)): ?>
<img class="profile-img" src="<?php echo htmlspecialchars($profile_image); ?>">
<?php else: ?>
<div style="text-align:center;">
<i class="fas fa-user-circle" style="font-size:80px;color:#3498db;"></i>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<label>Full Name</label>
<input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required>

<label>Email</label>
<input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

<label>Profile Image</label>
<input type="file" name="profile_image">

<!-- PASSWORD SECTION -->
<div class="section-title"> Change Password (optional)</div>

<input type="password" name="current_password" placeholder="Current Password">
<input type="password" name="new_password" placeholder="New Password">
<input type="password" name="confirm_password" placeholder="Confirm New Password">

<button type="submit" name="update_profile">
<i class="fas fa-save"></i> Update Profile
</button>

</form>

</div>

</div>

</body>
</html>