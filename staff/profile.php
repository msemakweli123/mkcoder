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
$user = mysqli_fetch_assoc($result) ?? [];

/* SAFE VALUES */
$fullname = $user['fullname'] ?? '';
$email    = $user['email'] ?? '';
$role     = $user['role'] ?? '';
$profile  = $user['profile_image'] ?? '';

$page_title = "Profile";
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

.profile-container{
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
}

.profile-img{
    text-align:center;
    margin-bottom:20px;
}

.profile-img img{
    width:130px;
    height:130px;
    border-radius:50%;
    object-fit:cover;
    border:4px solid #8B4513;
}

label{
    font-weight:600;
    display:block;
    margin-top:10px;
}

input{
    width:100%;
    padding:12px;
    margin-top:6px;
    border:1px solid #ddd;
    border-radius:8px;
    background:#f9fafb;
}

.readonly{
    background:#eee;
}

/* BUTTON */
.btn-settings{
    display:inline-block;
    margin-top:20px;
    padding:12px 18px;
    background:#8B4513;
    color:#fff;
    text-decoration:none;
    border-radius:8px;
    font-weight:600;
    transition:0.3s;
}

.btn-settings:hover{
    background:#6b3410;
}

</style>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<div class="profile-container">

    <div class="card">

        <h2><i class="fas fa-user"></i> My Profile</h2>

        <!-- PROFILE IMAGE -->
        <div class="profile-img">
            <img src="<?php
                if(!empty($profile)){
                    echo '../uploads/'.$profile.'?v='.time();
                }else{
                    echo 'https://ui-avatars.com/api/?name='.urlencode($fullname);
                }
            ?>">
        </div>

        <!-- INFO -->
        <label>Full Name</label>
        <input type="text" value="<?php echo htmlspecialchars($fullname); ?>" readonly>

        <label>Email</label>
        <input type="text" value="<?php echo htmlspecialchars($email); ?>" readonly>

        <label>Role</label>
        <input type="text" value="<?php echo htmlspecialchars($role); ?>" readonly>

        <!-- BUTTON TO SETTINGS -->
        <a href="settings.php" class="btn-settings">
            <i class="fas fa-gear"></i> Update Profile
        </a>

    </div>

</div>