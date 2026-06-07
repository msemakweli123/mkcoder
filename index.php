<?php
session_start();

/* AUTO REDIRECT IF LOGGED IN */
if(isset($_SESSION['id'])){

    switch($_SESSION['role']){

        case 'admin':
            header("Location: admin/dashboard.php");
            exit();

        case 'staff':
            header("Location: staff/dashboard.php");
            exit();

        case 'hod':
            header("Location: hod/dashboard.php");
            exit();

        case 'dean':
            header("Location: dean/dashboard.php");
            exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>U Procurement System</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

/* RESET */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Segoe UI, Arial, sans-serif;
}

/* BACKGROUND */
body{
    height:100vh;
    display:flex;
    background: #0b1220;
    color:white;
}

/* LEFT PANEL */
.left{
    flex:1;
    padding:80px;
    display:flex;
    flex-direction:column;
    justify-content:center;

    background: linear-gradient(135deg, #0b1220, #0f2a3d);
}

/* RIGHT PANEL */
.right{
    width:420px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);

    border-left:1px solid rgba(255,255,255,0.1);

    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    padding:40px;
}

/* LOGO */
.logo{
    font-size:28px;
    font-weight:bold;
    margin-bottom:20px;
    color:#38bdf8;
}

/* TITLE */
h1{
    font-size:40px;
    margin-bottom:15px;
}

p{
    font-size:15px;
    opacity:0.8;
    line-height:1.6;
    max-width:500px;
}

/* FEATURES */
.features{
    margin-top:30px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}

.feature{
    background:rgba(255,255,255,0.05);
    padding:15px;
    border-radius:10px;
    font-size:13px;
    display:flex;
    gap:10px;
    align-items:center;
}

.feature i{
    color:#38bdf8;
}

/* RIGHT PANEL BOX */
.login-box{
    text-align:center;
    width:100%;
}

.login-box h2{
    margin-bottom:10px;
}

.login-box p{
    font-size:13px;
    margin-bottom:25px;
}

/* BUTTON */
.btn{
    display:block;
    width:100%;
    padding:14px;
    background:#38bdf8;
    color:#0b1220;
    font-weight:bold;
    text-decoration:none;
    border-radius:10px;
    transition:0.3s;
}

.btn:hover{
    background:#0ea5e9;
    transform:translateY(-2px);
}

/* FOOTER */
.footer{
    position:absolute;
    bottom:15px;
    left:40px;
    font-size:12px;
    opacity:0.6;
}

/* RESPONSIVE */
@media(max-width:900px){
    body{
        flex-direction:column;
    }

    .right{
        width:100%;
        height:auto;
        border-left:none;
        border-top:1px solid rgba(255,255,255,0.1);
    }

    .left{
        padding:40px;
    }
}

</style>
</head>

<body>

<!-- LEFT SIDE -->
<div class="left">

    <div class="logo">
        <i class="fas fa-building-columns"></i> Procurement System
    </div>

    <h1>Smart Procurement Management</h1>

    <p>
        A centralized university system for managing procurement requests,
        approvals, tracking, and reporting across all departments in real time.
    </p>

    <div class="features">

        <div class="feature">
            <i class="fas fa-check-circle"></i>
            Automated Approvals
        </div>

        <div class="feature">
            <i class="fas fa-users"></i>
            Multi-Role Access
        </div>

        <div class="feature">
            <i class="fas fa-chart-line"></i>
            Real-time Tracking
        </div>

        <div class="feature">
            <i class="fas fa-lock"></i>
            Secure System
        </div>

    </div>

</div>

<!-- RIGHT SIDE -->
<div class="right">

    <div class="login-box">

        <h2>Welcome Back</h2>

        <p>Login to access your dashboard</p>

        <a href="login.php" class="btn">
            Login to System
        </a>

    </div>

</div>

<div class="footer">
    © <?php echo date('Y'); ?> University System
</div>

</body>
</html>