<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db.php';

$user_id = (int)($_SESSION['id'] ?? 0);

/* =========================
   USER INFO
========================= */
$userQuery = mysqli_query($conn, "
    SELECT fullname, profile_image 
    FROM users 
    WHERE id='$user_id' 
    LIMIT 1
");

$user = mysqli_fetch_assoc($userQuery);

$dean_name = $user['fullname'] ?? 'User';

/* PROFILE IMAGE */
$profile_img = !empty($user['profile_image']) ? $user['profile_image'] : null;

/* =========================
   NOTIFICATIONS COUNT
========================= */
$notifCount = 0;

$notifCountQuery = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM notifications 
    WHERE user_id='$user_id' 
    AND is_read=0
");

if ($notifCountQuery) {
    $row = mysqli_fetch_assoc($notifCountQuery);
    $notifCount = $row['total'] ?? 0;
}

/* =========================
   FETCH NOTIFICATIONS
========================= */
$notifQuery = mysqli_query($conn, "
    SELECT * 
    FROM notifications 
    WHERE user_id='$user_id' 
    ORDER BY created_at DESC 
    LIMIT 5
");
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
.topbar {
    position: fixed;
    top: 0;
    left: 250px;
    right: 0;
    height: 60px;
    background: #fff;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
    z-index: 1000;
}

.title {
    font-size: 16px;
    font-weight: bold;
    color: #2c3e50;
}

.right {
    display: flex;
    align-items: center;
    gap: 20px;
}

/* NOTIFICATION */
.notification {
    position: relative;
    cursor: pointer;
    font-size: 18px;
}

.notification .badge {
    position: absolute;
    top: -6px;
    right: -10px;
    background: red;
    color: white;
    font-size: 10px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* NOTIF BOX */
.notif-box {
    display: none;
    position: absolute;
    top: 45px;
    right: 120px;
    width: 280px;
    background: white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
    z-index: 9999;
}

.notif-box a {
    display: block;
    padding: 10px;
    font-size: 13px;
    color: #333;
    text-decoration: none;
    border-bottom: 1px solid #f1f1f1;
}

.notif-box a:hover {
    background: #f4f4f4;
}

/* PROFILE */
.profile {
    display: flex;
    align-items: center;
    cursor: pointer;
    position: relative;
}

.profile img {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: 2px solid #3498db;
    object-fit: cover;
}

.profile span {
    margin-left: 8px;
    font-size: 14px;
    color: #555;
}

/* DROPDOWN */
.dropdown {
    display: none;
    position: absolute;
    top: 50px;
    right: 0;
    background: white;
    width: 160px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-radius: 6px;
    overflow: hidden;
}

.dropdown a {
    display: block;
    padding: 10px;
    font-size: 14px;
    text-decoration: none;
    color: #333;
}

.dropdown a:hover {
    background: #f4f4f4;
}

/* SHOW STATE */
.show {
    display: block !important;
}
</style>

<div class="topbar">

    <div class="title">
         Dean Management System
    </div>

    <div class="right">

        <!-- NOTIFICATION -->
        <div style="position:relative;">

            <div class="notification" onclick="toggleNotif(event)">
                <i class="fas fa-bell"></i>

                <?php if($notifCount > 0): ?>
                    <div class="badge"><?php echo $notifCount; ?></div>
                <?php endif; ?>
            </div>

            <div class="notif-box" id="notifBox">

                <?php if(mysqli_num_rows($notifQuery) > 0): ?>
                    <?php while($n = mysqli_fetch_assoc($notifQuery)) { ?>
                        <a href="#">
                            <i class="fas fa-info-circle"></i>
                            <?php echo htmlspecialchars($n['message']); ?>
                        </a>
                    <?php } ?>
                <?php else: ?>
                    <a href="#">No notifications</a>
                <?php endif; ?>

            </div>

        </div>

        <!-- PROFILE -->
        <div class="profile" onclick="toggleProfile(event)">

            <?php if($profile_img): ?>
                <img src="<?php echo htmlspecialchars($profile_img); ?>">
            <?php else: ?>
                <i class="fas fa-user-circle" style="font-size:35px;color:#3498db;"></i>
            <?php endif; ?>

            <span><?php echo htmlspecialchars($dean_name); ?></span>

            <div class="dropdown" id="profileBox">
                <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
                <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
                <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

        </div>

    </div>
</div>

<script>
function toggleProfile(e){
    e.stopPropagation();

    let profileBox = document.getElementById("profileBox");
    let notifBox = document.getElementById("notifBox");

    notifBox.style.display = "none";

    profileBox.classList.toggle("show");
}

function toggleNotif(e){
    e.stopPropagation();

    let notifBox = document.getElementById("notifBox");
    let profileBox = document.getElementById("profileBox");

    profileBox.classList.remove("show");

    notifBox.style.display = (notifBox.style.display === "block") ? "none" : "block";
}

/* CLOSE OUTSIDE CLICK */
document.addEventListener("click", function(){
    document.getElementById("profileBox").classList.remove("show");
    document.getElementById("notifBox").style.display = "none";
});
</script>