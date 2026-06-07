<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

/* GET USER */
$user = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM users WHERE id=$id")
);

if(!$user){
    header("Location: manage_users.php");
    exit();
}

/* UPDATE USER */
if(isset($_POST['update'])){

    $name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = $_POST['role'];
    $department = $_POST['department'] ?? null;

    if(!empty($_POST['password'])){
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "UPDATE users SET 
                    fullname='$name',
                    email='$email',
                    password='$pass',
                    role='$role',
                    department='$department'
                WHERE id=$id";
    } else {

        $sql = "UPDATE users SET 
                    fullname='$name',
                    email='$email',
                    role='$role',
                    department='$department'
                WHERE id=$id";
    }

    mysqli_query($conn, $sql);

    header("Location: manage_users.php");
    exit();
}

$page_title = "Edit User";

include 'includes/header.php';
include 'includes/siderbar.php';
?>

<div class="main-content">

<?php include 'includes/topbar.php'; ?>

<div class="content">

    <div class="form-container">

        <h2><i class="fas fa-user-edit"></i> Edit User</h2>

        <form method="POST">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname"
                       value="<?php echo htmlspecialchars($user['fullname']); ?>"
                       required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"
                       value="<?php echo htmlspecialchars($user['email']); ?>"
                       required>
            </div>

            <div class="form-group">
                <label>New Password (optional)</label>
                <input type="password" name="password">
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" required>

                    <option value="staff" <?php if($user['role']=='staff') echo 'selected'; ?>>Staff</option>
                    <option value="hod" <?php if($user['role']=='hod') echo 'selected'; ?>>HOD</option>
                    <option value="dean" <?php if($user['role']=='dean') echo 'selected'; ?>>Dean</option>
                    <option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Admin</option>

                </select>
            </div>

            <!-- DEPARTMENT -->
            <div class="form-group">
                <label>Department</label>
                <input type="text" name="department"
                       value="<?php echo htmlspecialchars($user['department'] ?? ''); ?>"
                       placeholder="e.g css, Finance">
            </div>

            <button type="submit" name="update" class="submit-btn">
                <i class="fas fa-save"></i> Update User
            </button>

        </form>

    </div>

</div>

</div>