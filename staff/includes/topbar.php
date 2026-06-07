<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db.php';

/* ================= MARK AS READ ================= */
if(isset($_GET['mark_read'])){

    $id = (int)$_GET['mark_read'];

    mysqli_query($conn,
        "UPDATE notifications SET is_read = 1 WHERE id = $id"
    );

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

/* ================= NOTIFICATION COUNT ================= */
$notif_count = 0;

if (isset($conn) && isset($_SESSION['id'])) {

    $user_id = (int)$_SESSION['id'];

    $result = mysqli_query($conn,
        "SELECT COUNT(*) AS total
         FROM notifications
         WHERE user_id = $user_id
         AND is_read = 0"
    );

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $notif_count = $row['total'];
    }

    /* GET USER PROFILE IMAGE */
    $user = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT profile_image, fullname FROM users WHERE id=$user_id")
    );
}

$fullname = $_SESSION['fullname'] ?? 'User';
$avatar_name = urlencode($fullname);

/* PROFILE IMAGE LOGIC */
$profile_image = (!empty($user['profile_image']))
    ? "../uploads/" . $user['profile_image']
    : "https://ui-avatars.com/api/?name=" . $avatar_name . "&background=2563eb&color=fff";
?>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

.topbar{
    position:fixed;
    top:0;
    left:260px;
    right:0;
    height:70px;

    display:flex;
    justify-content:space-between;
    align-items:center;

    padding:0 25px;

    background:#fff;
    border-bottom:1px solid #e5e7eb;

    z-index:999;
    box-shadow:0 2px 10px rgba(0,0,0,.05);
}

.topbar-title{
    font-size:18px;
    font-weight:700;
}

.icon-btn{
    position:relative;
    width:42px;
    height:42px;
    border:none;
    border-radius:10px;
    background:#f3f4f6;
    cursor:pointer;
}

.icon-btn:hover{
    background:#e5e7eb;
}

.badge{
    position:absolute;
    top:-4px;
    right:-4px;
    background:red;
    color:white;
    font-size:11px;
    min-width:18px;
    height:18px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* PROFILE */
.profile{
    position:relative;
    display:flex;
    align-items:center;
    gap:10px;
    cursor:pointer;
}

.profile img{
    width:40px;
    height:40px;
    border-radius:50%;
    object-fit:cover;
}

/* DROPDOWN */
.dropdown{
    position:absolute;
    top:60px;
    right:0;
    width:200px;
    background:#fff;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
    border-radius:10px;
    display:none;
}

.dropdown.show{
    display:block;
}

.dropdown a{
    display:block;
    padding:12px;
    text-decoration:none;
    color:#333;
}

.dropdown a:hover{
    background:#f3f4f6;
}

/* NOTIFICATION */
.notif-wrapper{
    position:relative;
}

.notif-dropdown{
    position:absolute;
    right:0;
    top:50px;
    width:320px;
    background:#fff;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
    display:none;
}

.notif-dropdown.show{
    display:block;
}

.notif-header{
    padding:12px;
    font-weight:600;
    border-bottom:1px solid #eee;
}

.notif-body{
    max-height:250px;
    overflow-y:auto;
}

.notif-item{
    padding:12px;
    border-bottom:1px solid #f1f1f1;
    font-size:14px;
}

.notif-item.unread{
    background:#f0f9ff;
}

.notif-item a{
    font-size:12px;
    color:#2563eb;
    text-decoration:none;
    display:block;
    margin-top:5px;
}

</style>

<div class="topbar">

    <!-- TITLE -->
    <div class="topbar-title">
        <?php echo htmlspecialchars($page_title ?? 'Dashboard'); ?>
    </div>

    <div style="display:flex; align-items:center; gap:15px;">

        <!-- NOTIFICATIONS -->
        <div class="notif-wrapper">

            <button class="icon-btn" id="notifBtn">
                <i class="fas fa-bell"></i>

                <?php if($notif_count > 0): ?>
                    <span class="badge">
                        <?php echo $notif_count > 99 ? '99+' : $notif_count; ?>
                    </span>
                <?php endif; ?>
            </button>

            <div class="notif-dropdown" id="notifDropdown">

                <div class="notif-header">Notifications</div>

                <div class="notif-body">

                    <?php
                    if(isset($conn) && isset($_SESSION['id'])){

                        $notifs = mysqli_query($conn,
                            "SELECT * FROM notifications 
                             WHERE user_id = $user_id 
                             ORDER BY created_at DESC 
                             LIMIT 5"
                        );

                        if(mysqli_num_rows($notifs) > 0){
                            while($n = mysqli_fetch_assoc($notifs)){
                    ?>

                        <div class="notif-item <?php echo $n['is_read'] ? '' : 'unread'; ?>">
                            <?php echo htmlspecialchars($n['message']); ?>

                            <?php if(!$n['is_read']): ?>
                                <a href="?mark_read=<?php echo $n['id']; ?>">Mark as read</a>
                            <?php endif; ?>
                        </div>

                    <?php
                            }
                        } else {
                            echo "<div class='notif-item'>No notifications</div>";
                        }
                    }
                    ?>

                </div>

            </div>

        </div>

        <!-- PROFILE -->
        <div class="profile" id="profileBtn">

            <img src="<?php echo $profile_image; ?>">

            <i class="fas fa-chevron-down"></i>

            <div class="dropdown" id="profileDropdown">

                <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
                <a href="../logout.php" style="color:red;">
                    <i class="fas fa-right-from-bracket"></i> Logout
                </a>

            </div>

        </div>

    </div>
</div>

<script>

const profileBtn = document.getElementById('profileBtn');
const profileDropdown = document.getElementById('profileDropdown');

profileBtn.addEventListener('click', function(e){
    e.stopPropagation();
    profileDropdown.classList.toggle('show');
});

const notifBtn = document.getElementById('notifBtn');
const notifDropdown = document.getElementById('notifDropdown');

notifBtn.addEventListener('click', function(e){
    e.stopPropagation();
    notifDropdown.classList.toggle('show');
});

document.addEventListener('click', function(){
    profileDropdown.classList.remove('show');
    notifDropdown.classList.remove('show');
});

</script>