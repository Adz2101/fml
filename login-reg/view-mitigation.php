<?php
session_start(); // Initialize the session

require_once "database.php"; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION["user"]) || !isset($_SESSION["user"]["id"])) {
    // Redirect the user to the login page or display an error message
    echo "<p>Error: User not logged in</p>";
    exit();
}

// Retrieve the user ID from the session
$user_id = $_SESSION["user"]["id"];

// Query the database to retrieve approved mitigation plans for the logged-in user
$sql = "SELECT m.subject, mit.mitigation 
        FROM message m 
        JOIN mitigation mit ON m.id = mit.risk_id 
        WHERE m.user_id = ? AND m.mitigation_approved = 1 AND mit.user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $user_id, $user_id);

if (mysqli_stmt_execute($stmt)) {
    // Get the result set
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Display the table header
        echo "<table border='1'>";
        echo "<tr><th>Subject</th><th>Mitigation</th></tr>";

        // Display the approved mitigation plans in table rows
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['subject']}</td>";
            echo "<td>{$row['mitigation']}</td>";
            echo "</tr>";
        }

        // Close the table
        echo "</table>";
    } else {
        // No approved mitigation plans found for the user
        echo "<p>No approved mitigation plans found</p>";
    }
} else {
    // Error executing the SQL statement
    echo "<p>Error: " . mysqli_error($conn) . "</p>";
}

// Close the prepared statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
