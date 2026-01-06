<?php
//session_start();

require_once __DIR__.'/../../../database/dbconnection.php';
include_once __DIR__.'/../../../config/settings-configuration.php';
require_once __DIR__. '/../../../src/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ADMIN
{
    private $conn;

    //added
    private $settings;
    private $smtp_email;
    private $smtp_password;

    public function __construct()
    {
        $this->settings = new SystemConfig;
        $this->smtp_email = $this->settings->getSmtpEmail();
        $this->smtp_password = $this->settings->getSmtpPassword();

        $database = new Database();
        $this->conn = $database->dbConnection();
    }

    public function sendOtp($otp, $email)
    {
        if($email == NULL)
        {
            echo "<script> alert('No email found.'); window.location.href = '../../../';</script>";
            exit;
        }
        else
        {
            $stmt = $this->runQuery("SELECT * FROM user WHERE email = :email");
            $stmt->execute(array(":email" => $email));
            $stmt->fetch(PDO::FETCH_ASSOC);

            if($stmt->rowCount() > 0)
            {
                echo "<script> alert('Email already used! Use another one.'); window.location.href = '../../../';</script>";
                exit;
            }
            else
            {
                $_SESSION['OTP'] = $otp;

                $subject = "OTP VERIFICATION";
                $message = "
                <!DOCTYPE html>
                    <html>
                        <head>
                        <meta charset='UTF-8'>
                        <title>OTP Verification</title>
                        <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f5f5f5;
                            margin: 0;
                            padding: 0;
                        }

                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            padding: 30px;
                            background-color: #ffffff;
                            border-radius: 4px;
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                        }

                        h1 {
                            color: #333333;
                            font-size: 24px;
                            margin-bottom: 20px;
                        }

                        p {
                            color: #666666;
                            font-size: 16px;
                            margin-bottom: 10px;
                        }

                        .button {
                            display: inline-block;
                            padding: 12px 24px;
                            background-color: #0088cc;
                            color: #ffffff;
                            text-decoration: none;
                            border-radius: 4px;
                            font-size: 16px;
                            margin-top: 20px;
                        }

                        .logo {
                            display: block;
                            text-align: center;
                            margin-bottom: 30px;
                        }
                        </style>
                        </head>
                        <body>
                            <div class='container'>
                                <div class='logo'>
                                    <img src='cid:logo' alt='Logo' width='150'>
                                </div>
                                <h1>OTP Verification</h1>
                                <p>Hello, $email!</p>
                                <p>Your OTP is: <b>$otp</b></p>
                                <p>If you didn't request an OTP, please ignore this email.</p>
                                <p>Thank you!</p>
                            </div>
                        </body>
                    </html>";

                    $this->send_email($email, $message, $subject, $this->smtp_email, $this->smtp_password);
                    echo "<script>alert('We sent the OTP to $email'); window.location.href = '../../../verify-otp.php';</script>";
            }
        }
    }

    public function verifyOtp($username, $email, $password, $tokencode, $otp, $csrf_token)
    {
        if(!isset($_SESSION['OTP'])) {
            echo "<script>alert('No OTP found in session'); window.location.href = '../../../verify-otp.php';</script>";
            return;
        }
    
        if((string)$otp === (string)$_SESSION['OTP']) {
            unset($_SESSION['OTP']);
            $this->addAdmin($csrf_token, $username, $email, $password);
        }
        else {
            echo "<script>alert('OTP verification code doesn\\'t match!'); window.location.href = '../../../verify-otp.php';</script>";
        }
    }

    public function addAdmin($csrf_token, $username, $email, $password)
    {
        $stmt = $this->runQuery("SELECT * FROM user WHERE email = :email");
        $stmt->execute(array(":email" => $email));

        if($stmt->rowCount() > 0)
        {
            echo "<script> alert('Email already exists.'); window.location.href = '../../../';</script>";
            exit;
        }

        if(!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token))
        {
            echo "<script> alert('Invalid CSRF Token.'); window.location.href = '../../../';</script>";
            exit;        
        }

        unset($_SESSION['csrf_token']);

        $hash_password = md5($password);

        $stmt = $this->runQuery("INSERT INTO user (username, email, password) VALUES (:username, :email, :password)");
        $exec = $stmt->execute(array(
            ":username" => $username, 
            ":email" => $email,
            ":password" => $hash_password
        ));

        if($exec)
        {
            echo "<script> alert('Admin added successfully.'); window.location.href = '../../../';</script>";
        }
        else
        {
            echo "<script> alert('Failed to add admin.'); window.location.href = '../../../';</script>";
        }
    }

    public function adminSignin($email, $password, $csrf_token)
    {
        try
        {
            if(!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token))
            {
                echo "<script> alert('Invalid CSRF Token.'); window.location.href = '../../../';</script>";
                exit;        
            }

            unset($_SESSION['csrf_token']);

            $stmt = $this->runQuery("SELECT * FROM user WHERE email = :email AND status = :status");
            $stmt->execute(array(":email" => $email, ":status" => 'active'));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if we got a row (changed from rowCount check)
            if ($userRow) {
                if ($userRow['status'] == "active") {
                    if ($userRow['password'] == md5($password))
                    {
                        $activity = "Has Successfully signed in";
                        $user_id = $userRow['id'];
                        $this->logs($activity, $user_id);
                        $_SESSION['adminSession'] = $user_id;
                        echo "<script>alert('welcome'); window.location.href = '../';</script>";
                        exit;
                    }
                    else
                    {
                        echo "<script>alert('Password is incorrect'); window.location.href = '../../../';</script>";
                        exit;
                    }
                }
                else
                {
                    echo "<script>alert('Entered email is not verify'); window.location.href = '../../../';</script>";
                    exit;
                }
            }
            else
            {
                echo "<script>alert('No Account found'); window.location.href = '../../../';</script>";
                exit;
            }
        }
        catch(PDOException $ex)
        {
            echo "An Error Occurred :: " . $ex->getMessage();
        }
    }

    public function adminSignout()
    {
        // Check if user is logged in before logging out
        if($this->isUserLoggedIn()) {
            // Get user ID from session
            $user_id = $_SESSION['adminSession'];
            
            // Log the logout activity
            $activity = "Successfully Signed Out.";
            $this->logs($activity, $user_id);
        }
    
        // Clear the session
        unset($_SESSION['adminSession']);
        
        // Optionally destroy the entire session
        session_destroy();
    
        //echo "<script> alert('Signed Out Successfully.'); window.location.href = '../../../';</script>";
        echo "<script>window.location.href = '../../../';</script>";
        exit;
    }

    function send_email($email, $message, $subject, $smtp_email, $smtp_password)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->addAddress($email);
        $mail->Username = $smtp_email;
        $mail->Password = $smtp_password;
        $mail->setFrom($smtp_email, "Kala ko makakapag ml pako nyeta");
        $mail->Subject = $subject;
        $mail->msgHTML($message);
        $mail->Send();
    }


    public function logs($activity, $user_id)
    {
        $stmt = $this->runQuery("INSERT INTO logs (user_id, activity) VALUES (:user_id, :activity)");
        $stmt->execute(array(":user_id" => $user_id, ":activity" => $activity));
    }

    public function isUserLoggedIn()
    {
        if(isset($_SESSION['adminSession']))
        {
            return true;
        }
    }

    public function redirect()
    {
        echo "<script> alert('Admin must Log in first.'); window.location.href = '../../../';</script>";
        exit;
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);

        return $stmt;
    }
}

if (isset($_POST['btn-signup']))
{
    // $csrf_token = trim($_POST['csrf_token']);
    // $username = trim($_POST['username']);
    // $email = trim($_POST['email']);
    // $password = trim($_POST['password']);

    // $addAdmin = new ADMIN();
    // $addAdmin->addAdmin($csrf_token, $username, $email, $password);
    $_SESSION['not_verify_username'] = trim($_POST['username']);
    $_SESSION['not_verify_email'] = trim($_POST['email']);
    $_SESSION['not_verify_password'] = trim($_POST['password']);
    
    $email = trim($_POST['email']);
    $otp = rand(100000, 999999);
    
    $addAdmin = new ADMIN();
    $addAdmin->sendOtp($otp, $email);
}
    
if (isset($_POST['btn-verify'])) {
    $csrf_token = trim($_POST['csrf_token']);
    $username = $_SESSION['not_verify_username'];
    $email = $_SESSION['not_verify_email'];
    $password = $_SESSION['not_verify_password'];

    $tokencode = md5(uniqid(rand()));
    $otp = trim($_POST['otp']);

    $adminVerify = new ADMIN();
    $adminVerify->verifyOtp($username, $email, $password, $tokencode, $otp, $csrf_token);
}

if (isset($_POST['btn-signin']))
{
    $csrf_token = trim($_POST['csrf_token']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $adminSignin = new ADMIN();
    $adminSignin->adminSignin($email, $password, $csrf_token);
}

if(isset($_GET['admin_signout']))
{
    $adminSignout = new ADMIN();
    $adminSignout->adminSignout();
}
?>