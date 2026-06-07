<!-- FONT AWESOME -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- TOP HEADER -->
<div class="top-header">

    <!-- HEADER TITLE -->
    <div class="header-title">

        <h2>
            <i class="fas fa-user-shield"></i>
            <?php echo $page_title ?? 'Admin Dashboard'; ?>
        </h2>

        <p>
            <i class="fas fa-handshake"></i>
            Welcome back, <?php echo $_SESSION['fullname']; ?>
        </p>

    </div>

    <!-- USER INFO -->
    <div class="user-info">

        <div class="profile-dropdown">

            <!-- PROFILE BUTTON -->
            <button class="profile-btn" onclick="toggleProfileMenu()">

                <div class="profile-left">

                    <i class="fas fa-user-circle profile-user-icon"></i>

                    <div class="profile-text">

                        <span class="user-name">
                            <?php echo $_SESSION['fullname']; ?>
                        </span>

                        <span class="user-role">
                            <i class="fas fa-user-shield"></i>
                            Admin
                        </span>

                    </div>

                </div>

                <i class="fas fa-chevron-down dropdown-icon"></i>

            </button>

            <!-- PROFILE CARD -->
            <div class="profile-menu" id="profileMenu">

                <!-- PROFILE TOP -->
                <div class="profile-header">

                    <i class="fas fa-user-shield profile-main-icon"></i>

                    <h3><?php echo $_SESSION['fullname']; ?></h3>

                    <p>System Administrator</p>

                </div>

                <!-- MENU ITEMS -->

                <a href="profile.php" class="profile-link">

                    <i class="fas fa-user"></i>

                    <span>My Profile</span>

                </a>

                <a href="settings.php" class="profile-link">

                    <i class="fas fa-gear"></i>

                    <span>Settings</span>

                </a>

                <a href="../logout.php" class="profile-link logout-link">

                    <i class="fas fa-right-from-bracket"></i>

                    <span>Logout</span>

                </a>

            </div>

        </div>

    </div>

</div>

<!-- STYLE -->
<style>

.top-header{
    background:#fff;
    padding:20px 25px;
    border-radius:12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 12px rgba(0,0,0,0.08);
    margin-bottom:25px;
    position:relative;
    flex-wrap:wrap;
}

.header-title h2{
    font-size:28px;
    color:#8B4513;
    margin-bottom:6px;
    display:flex;
    align-items:center;
    gap:10px;
}

.header-title h2 i{
    color:#D2691E;
}

.header-title p{
    color:#666;
    font-size:15px;
    display:flex;
    align-items:center;
    gap:8px;
}

.header-title p i{
    color:#D2691E;
}

/* USER INFO */

.user-info{
    position:relative;
}

/* PROFILE BUTTON */

.profile-btn{
    background:#8B4513;
    color:white;
    border:none;
    border-radius:50px;
    padding:10px 18px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:15px;
    cursor:pointer;
    min-width:250px;
    transition:0.3s;
}

.profile-btn:hover{
    background:#A0522D;
}

.profile-left{
    display:flex;
    align-items:center;
    gap:12px;
}

.profile-user-icon{
    font-size:40px;
    color:white;
}

.profile-text{
    display:flex;
    flex-direction:column;
    align-items:flex-start;
}

.user-name{
    font-size:15px;
    font-weight:bold;
}

.user-role{
    font-size:12px;
    opacity:0.9;
    display:flex;
    align-items:center;
    gap:5px;
}

.dropdown-icon{
    font-size:14px;
}

/* PROFILE MENU */

.profile-menu{
    position:absolute;
    top:70px;
    right:0;
    width:280px;
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 5px 20px rgba(0,0,0,0.15);
    display:none;
    z-index:999;
    animation:fadeIn 0.3s ease;
}

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(-10px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* PROFILE HEADER */

.profile-header{
    background:linear-gradient(135deg,#8B4513,#D2691E);
    color:white;
    text-align:center;
    padding:30px 20px;
}

.profile-main-icon{
    font-size:65px;
    margin-bottom:12px;
}

.profile-header h3{
    margin-bottom:6px;
    font-size:20px;
}

.profile-header p{
    font-size:14px;
    opacity:0.9;
}

/* PROFILE LINKS */

.profile-link{
    display:flex;
    align-items:center;
    gap:15px;
    padding:16px 20px;
    text-decoration:none;
    color:#333;
    border-bottom:1px solid #eee;
    transition:0.3s;
    font-size:15px;
}

.profile-link:hover{
    background:#f5f5f5;
    color:#8B4513;
}

.profile-link i{
    width:20px;
    text-align:center;
    color:#8B4513;
}

.logout-link{
    color:#c0392b;
}

.logout-link i{
    color:#c0392b;
}

/* RESPONSIVE */

@media(max-width:768px){

    .top-header{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .profile-btn{
        width:100%;
        min-width:100%;
    }

    .profile-menu{
        width:100%;
        right:auto;
        left:0;
    }

    .header-title h2{
        font-size:22px;
    }
}

</style>

<!-- SCRIPT -->
<script>

function toggleProfileMenu(){

    let profileMenu = document.getElementById("profileMenu");

    if(profileMenu.style.display === "block"){

        profileMenu.style.display = "none";

    }else{

        profileMenu.style.display = "block";
    }
}

/* CLOSE MENU WHEN CLICK OUTSIDE */

window.addEventListener("click", function(e){

    let dropdown = document.querySelector(".profile-dropdown");

    if(!dropdown.contains(e.target)){

        document.getElementById("profileMenu").style.display = "none";
    }

});

</script>