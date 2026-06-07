<?php
session_start();
include 'includes/db.php';

$error = "";

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // SAFE QUERY (prevents SQL injection)
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND status = 'active' LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){

        $row = $result->fetch_assoc();

        if(password_verify($password, $row['password'])){

            // CLEAN DATA
            $role = strtolower(trim($row['role']));

            // ================= SESSION =================
            $_SESSION['id'] = $row['id'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['role'] = $role;
            $_SESSION['department'] = $row['department'] ?? ''; // IMPORTANT FIX

            // ================= REDIRECT =================
            switch($role){

                case 'admin':
                    header("Location: admin/dashboard.php");
                    break;

                case 'staff':
                    header("Location: staff/dashboard.php");
                    break;

                case 'hod':
                    header("Location: hod/dashboard.php");
                    break;

                case 'dean':
                    header("Location: dean/dashboard.php");
                    break;

                default:
                    $error = "Invalid role assigned to user";
                    break;
            }

            exit();

        } else {
            $error = "Invalid Email or Password";
        }

    } else {
        $error = "Account not found or inactive";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Login System</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial, sans-serif;
}

body{
    background: #0b1220;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.main-container{
    width:90%;
    min-height:85vh;
    display:flex;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 0 15px rgba(0,0,0,0.3);
}

.left-side{
    width:40%;
    background:saddlebrown;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:40px;
}

.login-box{
    width:100%;
    background:white;
    padding:40px;
    border-radius:10px;
}

.login-box h2{
    text-align:center;
    margin-bottom:30px;
    color:saddlebrown;
}

.input-box{
    margin-bottom:20px;
    position:relative;
}

.input-box i{
    position:absolute;
    left:12px;
    top:50%;
    transform:translateY(-50%);
    color:sienna;
}

.input-box input{
    width:100%;
    padding:12px 12px 12px 40px;
    border:1px solid peru;
    border-radius:5px;
}

.login-btn{
    width:100%;
    padding:12px;
    background:sienna;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

.login-btn:hover{
    background:brown;
}

.error{
    color:red;
    text-align:center;
    margin-bottom:10px;
}

.right-side{
    width:60%;
    background: #0b1220;
    padding:60px;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.right-side h1{
    color:saddlebrown;
    font-size:36px;
}

.features li{
    list-style:none;
    margin-top:10px;
    color:sienna;
}

@media(max-width:992px){
    .main-container{
        flex-direction:column;
    }

    .left-side,.right-side{
        width:100%;
    }

    .right-side{
        padding:30px;
    }
}

</style>

</head>

<body>

<div class="main-container">

    <!-- LOGIN -->
    <div class="left-side">

        <div class="login-box">

            <h2><i class="fas fa-user-lock"></i> Login</h2>

            <?php if($error){ ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>

            <form method="POST">

                <div class="input-box">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="input-box">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit" name="login" class="login-btn">
                    <i class="fas fa-right-to-bracket"></i> Login
                </button>

            </form>

        </div>

    </div>

    <!-- INFO -->
    <div class="right-side">

        <h1>Procurement System</h1>

        <ul class="features">
            <li><i class="fas fa-check-circle"></i> Online Requests</li>
            <li><i class="fas fa-check-circle"></i> Faster Approval</li>
            <li><i class="fas fa-check-circle"></i> Tracking System</li>
        </ul>

    </div>

</div>

</body>
</html>