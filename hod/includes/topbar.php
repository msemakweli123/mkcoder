<?php
if(!isset($_SESSION)) session_start();
include '../includes/db.php';

$user_id = $_SESSION['id'] ?? 0;

/* REAL NOTIFICATIONS */
$notif_count = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT COUNT(*) as total 
     FROM notifications 
     WHERE user_id=$user_id AND is_read=0")
)['total'] ?? 0;

$notif_result = mysqli_query($conn,
"SELECT * FROM notifications 
 WHERE user_id=$user_id 
 ORDER BY id DESC LIMIT 5");
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

/* TOPBAR */
.topbar{
    position:fixed;
    top:0;
    left:289px;
    right:0;
    height:65px;
    background:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 20px;
    box-shadow:0 2px 12px rgba(0,0,0,0.08);
    z-index:1000;
}

/* MAIN CONTENT FIX */
.main-content{
    margin-left:250px;
    padding-top:85px;
}

/* TITLE */
.title{
    font-size:18px;
    font-weight:600;
    color:#6b3f1d;
    display:flex;
    align-items:center;
    gap:10px;
}

/* RIGHT SIDE */
.right{
    display:flex;
    align-items:center;
    gap:15px;
}

/* ICON BUTTON */
.icon-btn{
    position:relative;
    width:38px;
    height:38px;
    border:none;
    border-radius:8px;
    background:#f4f4f4;
    cursor:pointer;
}

/* BADGE */
.badge{
    position:absolute;
    top:-5px;
    right:-5px;
    background:red;
    color:white;
    font-size:10px;
    width:18px;
    height:18px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* PROFILE */
.profile{
    display:flex;
    align-items:center;
    gap:10px;
    cursor:pointer;
    padding:5px 10px;
    border-radius:8px;
}

.profile:hover{
    background:#f4f4f4;
}

.profile img{
    width:35px;
    height:35px;
    border-radius:50%;
}

/* DROPDOWN */
.dropdown{
    position:absolute;
    right:20px;
    top:65px;
    width:260px;
    background:white;
    border-radius:10px;
    box-shadow:0 5px 20px rgba(0,0,0,0.15);
    display:none;
    overflow:hidden;
}

.item{
    padding:10px;
    border-bottom:1px solid #eee;
    font-size:14px;
    display:flex;
    gap:8px;
    align-items:center;
}

.item:hover{
    background:#f5f5f5;
}

</style>

<div class="topbar">

    <!-- TITLE -->
    <div class="title">
        <i class="fas fa-bars"></i>
        <?php echo $page_title ?? 'Dashboard'; ?>
    </div>

    <!-- RIGHT -->
    <div class="right">

        <!-- NOTIFICATIONS -->
        <div class="notif">

            <button class="icon-btn" onclick="toggleNotif()">
                <i class="fas fa-bell"></i>

                <?php if($notif_count > 0){ ?>
                    <span class="badge"><?php echo $notif_count; ?></span>
                <?php } ?>

            </button>

            <div class="dropdown" id="notifBox">

                <?php if(mysqli_num_rows($notif_result) > 0){ ?>

                    <?php while($n = mysqli_fetch_assoc($notif_result)){ ?>
                        <div class="item">
                            <i class="fas fa-info-circle"></i>
                            <?php echo htmlspecialchars($n['message']); ?>
                        </div>
                    <?php } ?>

                <?php } else { ?>
                    <div class="item">No notifications</div>
                <?php } ?>

            </div>

        </div>

        <!-- PROFILE -->
        <div class="profile" onclick="toggleProfile()">

            <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['fullname'] ?? 'User'; ?>">

            <span><?php echo $_SESSION['fullname'] ?? 'User'; ?></span>

            <i class="fas fa-chevron-down"></i>

        </div>

    </div>

</div>

<!-- PROFILE MODAL -->
<div id="profileModal" class="dropdown">

    <div class="item">
        <i class="fas fa-user"></i> Profile
    </div>

    <div class="item">
        <i class="fas fa-image"></i> Change Photo
    </div>

    <div class="item">
        <i class="fas fa-gear"></i> Settings
    </div>

    <a class="item" href="../logout.php" style="color:red;">
        <i class="fas fa-right-from-bracket"></i> Logout
    </a>

</div>

<script>

/* NOTIFICATION TOGGLE */
function toggleNotif(){
    let box = document.getElementById('notifBox');
    box.style.display = (box.style.display === 'block') ? 'none' : 'block';
}

/* PROFILE TOGGLE */
function toggleProfile(){
    let box = document.getElementById('profileModal');
    box.style.display = (box.style.display === 'block') ? 'none' : 'block';
}

/* CLOSE OUTSIDE CLICK */
document.addEventListener('click', function(e){

    if(!e.target.closest('.notif')){
        document.getElementById('notifBox').style.display = 'none';
    }

    if(!e.target.closest('.profile')){
        document.getElementById('profileModal').style.display = 'none';
    }

});

</script>