<?php
session_start();

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit();
}

include '../includes/db.php';

$page_title = "Add User";

/* ================= ADD USER LOGIC ================= */
$success = "";
$error = "";

if(isset($_POST['add'])){

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    $department = $_POST['department'] ?? null;

    if(empty($fullname) || empty($email) || empty($password) || empty($role)){
        $error = "All required fields must be filled!";
    } else {

        // check email
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

        if(mysqli_num_rows($check) > 0){
            $error = "Email already exists!";
        } else {

            // HASH PASSWORD
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // INSERT USER
            $insert = mysqli_query($conn,
                "INSERT INTO users (fullname, email, password, role, department)
                 VALUES ('$fullname', '$email', '$hashedPassword', '$role', '$department')"
            );

            if($insert){
                $success = "User added successfully!";
            } else {
                $error = "Failed to add user!";
            }
        }
    }
}

include 'includes/header.php';
?>

<?php include 'includes/siderbar.php'; ?>

<div class="main-content">

<?php include 'includes/topbar.php'; ?>

<div class="content">

<div class="form-container">

    <?php if($success) echo "<div class='msg success'>$success</div>"; ?>
    <?php if($error) echo "<div class='msg error'>$error</div>"; ?>

    <form method="POST">

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="fullname" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role" id="role" required>
                <option value="">Select Role</option>
                <option value="staff">Staff</option>
                <option value="hod">HOD</option>
                <option value="dean">Dean</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <!-- DEPARTMENT (ONLY FOR STAFF & HOD) -->
        <div class="form-group">
            <label>Department</label>
            <input type="text" name="department" placeholder="e.g ICT, HR, Finance">
        </div>

        <button type="submit" name="add" class="submit-btn">
            <i class="fas fa-user-plus"></i>
            Add User
        </button>

    </form>

</div>

</div>

</div>

</body>
</html>