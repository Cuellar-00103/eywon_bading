<?php
include_once 'config/settings-configuration.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .otp-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            background-color: white;
        }
        .otp-title {
            text-align: center;
            margin-bottom: 30px;
            color: #343a40;
        }
        .otp-input {
            letter-spacing: 10px;
            font-size: 1.5rem;
            text-align: center;
            height: 50px;
        }
        .btn-verify {
            width: 100%;
            padding: 10px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="otp-container">
            <h1 class="otp-title">Enter OTP</h1>
            <form action="dashboard/admin/authentication/admin-class.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="mb-3">
                    <input type="number" 
                           name="otp" 
                           class="form-control otp-input" 
                           placeholder="Enter OTP" 
                           required>
                </div>
                
                <button type="submit" 
                        name="btn-verify" 
                        class="btn btn-primary btn-verify">
                    VERIFY
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>