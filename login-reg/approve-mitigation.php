<?php
session_start();
require_once "database.php"; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION["admin"])) {
    // Redirect if not logged in as admin
    header("Location: login.php");
    exit();
}

// Check if risk ID is provided
if (!isset($_GET["risk_id"])) {
    // Redirect if risk ID is missing
    header("Location: admin-dashboard.php");
    exit();
}

$risk_id = $_GET["risk_id"];

// Update mitigation approval status in the database
$sql = "UPDATE message SET mitigation_approved = 1 WHERE id =?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $risk_id);

if (mysqli_stmt_execute($stmt)) {
    // Mitigation approved successfully
    echo "Mitigation approved successfully";
    // Optionally, you can send an email notification to the user here
} else {
    // Error updating mitigation approval status
    echo "Error: ". mysqli_error($conn);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>