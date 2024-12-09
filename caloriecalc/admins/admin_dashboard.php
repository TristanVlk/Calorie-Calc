<?php
session_start();
include '../includes/db_connect.php';

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');  // Redirect if not logged in as admin
    exit();
}

// Fetch active user accounts
$stmt = $conn->prepare("SELECT id, username, status FROM users");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/adminstyle.css">
    <link rel="icon" type="image/x-icon" href="../images/icon1.png">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-dark bg-transparent">
        <div class="container">
            <!-- logo -->
            <a class="navbar-brand display-1 fw-normal" href="#">Calorie<span class="rounded-pill" style="color: #f1683a; background-color: #2c3336;">Calc</span></a>
            <!-- toggle -->
            <button class="navbar-toggler shadow-none " type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="sidebar offcanvas offcanvas-start " tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title text-white fs-2" id="offcanvasDarkNavbarLabel">Calorie<span class="rounded-pill" style="color: #f1683a; background-color: #2c3336;">Calc</span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                     
                  
                    <br>
                    <div class="hi mt-auto">
            <a class="nav-link text-danger " href="../pages/logout.php">Logout</a>
        </div>
                </div>
            </div>
        </div>
    </nav>
<h1>Admin Dashboard</h1>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <?php if ($row['status'] == 'active'): ?>
                        <a href="block_user.php?id=<?php echo $row['id']; ?>" class="action-button">Block</a>
                    <?php elseif ($row['status'] == 'blocked'): ?>
                        <a href="unblock_user.php?id=<?php echo $row['id']; ?>"class="action-button">Unblock</a>
                    <?php endif; ?>
                    <a href="#" onclick="showEditForm(<?php echo $row['id']; ?>, '<?php echo $row['username']; ?>')"class="action-button">Edit</a>
                    <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');"class="action-button">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Edit User Form -->
<div id="editUserForm" style="display:none;">
    <h2>Edit User</h2>
    <form id="editForm" method="POST" action="edit_user.php">
        <input type="hidden" name="id" id="editUserId">
        <label for="username">New Username:</label>
        <input type="text" name="username" id="editUsername" required>
        <button type="submit">Update</button>
        <button type="button" onclick="hideEditForm()">Cancel</button>
    </form>
</div>

<script>
function showEditForm(id, username) {
    document.getElementById('editUserId').value = id;
    document.getElementById('editUsername').value = username;
    document.getElementById('editUserForm').style.display = 'block';
}

function hideEditForm() {
    document.getElementById('editUserForm').style.display = 'none';
}
</script>


</body>
</html>
