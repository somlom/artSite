<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $suggestion = $conn->real_escape_string($_POST['suggestion']);

    // Insert suggestion into the database
    $conn->query("INSERT INTO suggestions (suggestion) VALUES ('$suggestion')");

    // Close the database connection
    $conn->close();

    // Send response to the client
    echo "Suggestion saved successfully.";
} else {
    // Handle invalid request
    echo "Invalid request.";
}
?>
