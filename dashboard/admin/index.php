<?php
require_once "authentication/admin-class.php";
$admin = new ADMIN();

if(!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

$stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

//echo $_SESSION['csrf_token']; // Check the token value
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../../src/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Welcome Shoppers!</a>
            <div class="d-flex">
                <span class="navbar-text me-3 text-white">
                    Welcome, <?php echo htmlspecialchars($user_data['username']); ?>
                </span>
                <a href="authentication/admin-class.php?admin_signout" class="btn btn-danger">Sign Out</a>
            </div>
        </div>
    </nav>

    <div class="container admin-dashboard">
        <div class="row">
            <div class="col-md-12">
                <div class="user-welcome">
                    <h1 class="display-4">Welcome, <?php echo htmlspecialchars($user_data['username']); ?>!</h1>
                    <p class="lead">You have successfully logged in.</p>
                </div>
            </div>
        </div>

        <!-- Add your dashboard content here -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Users</h5>
                        <p class="card-text">Manage system users</p>
                        <a href="#" class="btn btn-primary">Go to Users</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Settings</h5>
                        <p class="card-text">System configuration</p>
                        <a href="#" class="btn btn-primary">Go to Settings</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Reports</h5>
                        <p class="card-text">View system reports</p>
                        <a href="#" class="btn btn-primary">Go to Reports</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>