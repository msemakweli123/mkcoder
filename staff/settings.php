<?php
session_start();

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'staff'){
    header("Location: ../index.php");
    exit();
}

include '../includes/db.php';

$user_id = (int)$_SESSION['id'];

/* GET USER */
$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id");
$user = mysqli_fetch_assoc($result);

/* SAFE DEFAULTS (FIXED PROPERLY) */
$fullname = $user['fullname'] ?? '';
$email    = $user['email'] ?? '';
$password = $user['password'] ?? '';
$profile_image = $user['profile_image'] ?? '';

$message = "";

/* ================= UPDATE PROFILE ================= */
if(isset($_POST['update_profile'])){

    $fullname = mysqli_real_escape_string($conn, $_POST['fullname'] ?? '');
    $email    = mysqli_real_escape_string($conn, $_POST['email'] ?? '');

    $image = $profile_image;

    /* IMAGE UPLOAD */
    if(!empty($_FILES['profile_image']['name'])){

        $target_dir = "../uploads/";

        if(!is_dir($target_dir)){
            mkdir($target_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $file_name;

        $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed = ['jpg','jpeg','png','gif'];

        if(in_array($ext, $allowed)){
            if(move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)){
                $image = $file_name;
            }
        }
    }

    $update = mysqli_query($conn,
        "UPDATE users 
         SET fullname='$fullname',
             email='$email',
             profile_image='$image'
         WHERE id=$user_id"
    );

    if($update){
        $_SESSION['fullname'] = $fullname;

        /* RELOAD USER */
        $result = mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id");
        $user = mysqli_fetch_assoc($result);

        $message = "Profile updated successfully!";
    } else {
        $message = "Failed to update profile.";
    }
}

/* ================= CHANGE PASSWORD ================= */
if(isset($_POST['change_password'])){

    $old = $_POST['old_password'] ?? '';
    $new = $_POST['new_password'] ?? '';

    if(password_verify($old, $password)){

        $hashed = password_hash($new, PASSWORD_DEFAULT);

        mysqli_query($conn,
            "UPDATE users 
             SET password='$hashed'
             WHERE id=$user_id"
        );

        $message = "Password changed successfully!";
    } else {
        $message = "Old password is incorrect.";
    }
}

$page_title = "Settings";
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

.settings-container{
    margin-left:260px;
    padding:100px 25px;
    background:#f5f7fb;
    min-height:100vh;
}

.card{
    background:#fff;
    max-width:700px;
    margin:auto;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
    margin-bottom:20px;
}

input{
    width:100%;
    padding:12px;
    margin:8px 0;
    border:1px solid #ddd;
    border-radius:8px;
}

button{
    width:100%;
    padding:12px;
    background:#8B4513;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

button:hover{
    background:#6b3410;
}

.msg{
    text-align:center;
    margin-bottom:15px;
    color:green;
    font-weight:600;
}

.profile-preview{
    text-align:center;
    margin-bottom:15px;
}

.profile-preview img{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    border:4px solid #8B4513;
}

@media(max-width:768px){
    .settings-container{
        margin-left:0;
    }
}
</style>


<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<div class="settings-container">

    <div class="card">

        <h2><i class="fas fa-user"></i> Update Profile</h2>

        <?php if($message): ?>
            <div class="msg"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- PROFILE IMAGE -->
        <div class="profile-preview">
            <img src="<?php
                echo !empty($profile_image)
                    ? '../uploads/'.$profile_image.'?v='.time()
                    : 'https://ui-avatars.com/api/?name='.urlencode($fullname);
            ?>">
        </div>

        <form method="POST" enctype="multipart/form-data">

            <input type="text" name="fullname"
                   value="<?php echo htmlspecialchars($fullname); ?>"
                   required>

            <input type="email" name="email"
                   value="<?php echo htmlspecialchars($email); ?>"
                   required>

            <input type="file" name="profile_image" accept="image/*">

            <button type="submit" name="update_profile">
                Update Profile
            </button>

        </form>

    </div>

    <div class="card">

        <h2><i class="fas fa-lock"></i> Change Password</h2>

        <form method="POST">

            <input type="password" name="old_password" placeholder="Old Password" required>

            <input type="password" name="new_password" placeholder="New Password" required>

            <button type="submit" name="change_password">
                Change Password
            </button>

        </form>

    </div>

</div>