<?php
if(!isset($_SESSION)) session_start();
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

.sidebar{
    width:250px;
    height:100vh;
    background:#6b3f1d;
    position:fixed;
    left:0;
    top:0;
    padding:20px;
    color:white;
    overflow-y:auto;
}

.sidebar h2{
    margin-bottom:20px;
    font-size:18px;
}

.sidebar a{
    display:flex;
    align-items:center;
    gap:10px;
    color:white;
    text-decoration:none;
    padding:12px;
    margin-bottom:6px;
    border-radius:6px;
    transition:0.2s;
}

.sidebar a:hover{
    background:#8b5a2b;
}

.section-title{
    margin-top:15px;
    font-size:12px;
    opacity:0.7;
}

</style>

<div class="sidebar">

    <h2><i class="fas fa-box"></i> Procurement</h2>

    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>

    <div class="section-title">REQUESTS</div>

    <a href="my_requests.php"><i class="fas fa-file"></i> My Requests</a>
    <a href="approved_requests.php"><i class="fas fa-check"></i> Approved</a>

    <div class="section-title">SYSTEM</div>

    <a href="../logout.php"><i class="fas fa-right-from-bracket"></i> Logout</a>

</div>