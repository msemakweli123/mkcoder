<?php
session_start();

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit();
}

include '../includes/db.php';

$page_title = "Manage Users";

include 'includes/header.php';
include 'includes/siderbar.php';
?>

<div class="main-content">

    <?php include 'includes/topbar.php'; ?>

    <div class="content">

        <div class="data-table">

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                <?php
                $result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");

                while($row = mysqli_fetch_assoc($result)){

                    $role_class = "role-".$row['role'];
                    $status = $row['status'] ?? 'active';
                    $department = $row['department'] ?? '-';

                ?>

                    <tr>

                        <td><?php echo $row['id']; ?></td>

                        <td><?php echo htmlspecialchars($row['fullname']); ?></td>

                        <td><?php echo htmlspecialchars($row['email']); ?></td>

                        <td>
                            <span class="role-badge <?php echo $role_class; ?>">
                                <?php echo strtoupper($row['role']); ?>
                            </span>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($department); ?>
                        </td>

                        <td>
                            <span class="status-badge <?php echo $status; ?>">
                                <?php echo strtoupper($status); ?>
                            </span>
                        </td>

                        <td>

                            <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="edit-btn">
                                <i class="fas fa-pen"></i> Edit
                            </a>

                            <?php if($status == 'active'){ ?>

                                <a href="toggle_user.php?id=<?php echo $row['id']; ?>&status=suspended"
                                   class="suspend-btn">
                                    <i class="fas fa-ban"></i> Suspend
                                </a>

                            <?php } else { ?>

                                <a href="toggle_user.php?id=<?php echo $row['id']; ?>&status=active"
                                   class="activate-btn">
                                    <i class="fas fa-check"></i> Activate
                                </a>

                            <?php } ?>

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

    <?php include 'includes/footer.php'; ?>

</div>