<?php
include_once "./database/dbconnection.php";

$message = "";

if(isset($_POST['register'])){

    $database = new Database();
    $conn = $database->dbConnection();

    $first_name  = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name   = $_POST['last_name'];
    $username    = $_POST['username'];
    $email       = $_POST['email'];
    $phone       = $_POST['phone'];
    $password    = $_POST['password'];

    // 1️⃣ Check if email already exists
    $check = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check->execute([$email]);

    if($check->rowCount() > 0){
        $message = "❌ Email already exists!";
    } else {

        // 2️⃣ Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 3️⃣ Insert user (inactive by default)
        $stmt = $conn->prepare("INSERT INTO users 
        (first_name, middle_name, last_name, username, email, phone, password, role, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'user', 'inactive')");

        $stmt->execute([
            $first_name,
            $middle_name,
            $last_name,
            $username,
            $email,
            $phone,
            $hashed_password
        ]);

        $user_id = $conn->lastInsertId();

        // 4️⃣ Generate OTP
        $otp = rand(100000, 999999);

        $otp_stmt = $conn->prepare("INSERT INTO otp_codes (user_id, otp_code, expires_at)
        VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 5 MINUTE))");

        $otp_stmt->execute([$user_id, $otp]);

        $message = "✅ Registered successfully! Your OTP is: <b>$otp</b>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

<h2>Register</h2>

<?php
if(!empty($message)){
    echo "<p>$message</p>";
}
?>

<form method="POST">
    <input type="text" name="first_name" placeholder="First Name" required><br><br>
    <input type="text" name="middle_name" placeholder="Middle Name"><br><br>
    <input type="text" name="last_name" placeholder="Last Name" required><br><br>
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="text" name="phone" placeholder="Phone"><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>

    <button type="submit" name="register">Register</button>
</form>

</body>
</html>