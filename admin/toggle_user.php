<?php
session_start();

include '../includes/db.php';

/* CHECK ADMIN LOGIN */
if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit();
}

/* GET DATA */
if(isset($_GET['id']) && isset($_GET['status'])){

    $id = intval($_GET['id']);
    $status = $_GET['status'];

    /* VALIDATE STATUS ONLY */
    $allowed_status = ['active', 'suspended'];

    if(in_array($status, $allowed_status)){

        $query = "UPDATE users SET status='$status' WHERE id=$id";
        mysqli_query($conn, $query);
    }
}

/* REDIRECT BACK */
header("Location: manage_users.php");
exit();
?>