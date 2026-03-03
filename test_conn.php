<?php
require_once 'database/dbconnection.php';

$db = new Database();
$conn = $db->dbConnection();

if($conn) {
    echo "Database connected successfully!";
} else {
    echo "Connection failed!";
}
?>