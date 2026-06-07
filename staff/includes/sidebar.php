<div class="sidebar">

    <div class="sidebar-header">

        <h3>
            <i class="fas fa-user-tie"></i>
            Staff Panel
        </h3>

        <p>Procurement System</p>

    </div>

    <div class="sidebar-menu">

        <a href="dashboard.php" class="menu-item active">
            <i class="fas fa-chart-line"></i>
            Dashboard
        </a>

        <a href="my_requests.php" class="menu-item">
            <i class="fas fa-file-alt"></i>
            My Requests
        </a>

        <a href="new_request.php" class="menu-item">
            <i class="fas fa-plus"></i>
            New Request
        </a>

        <a href="../logout.php" class="menu-item">
            <i class="fas fa-right-from-bracket"></i>
            Logout
        </a>

    </div>

</div>

<style>

.sidebar{
    width:260px;
    height:100vh;
    position:fixed;
    top:0;
    left:0;

    background:#8B4513;
    color:white;

    z-index:1000; /* IMPORTANT */
    display:block;
}

.sidebar-header{
    text-align:center;
    padding:20px;
    border-bottom:1px solid rgba(255,255,255,0.2);
}

.sidebar-menu{
    display:flex;
    flex-direction:column;
}

.menu-item{
    padding:15px 20px;
    color:white;
    text-decoration:none;
    display:flex;
    gap:10px;
    transition:0.3s;
}

.menu-item:hover{
    background:#A0522D;
}

.menu-item.active{
    background:#D2691E;
}

</style>