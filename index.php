<!-- <?php
// Specify the path to the HTML file
$file = 'templates/index.html';

// Check if the file exists
if (file_exists($file)) {
    // Set the Content-Type header to text/html
    header('Content-Type: text/html');

    // Output the contents of the file
    readfile($file);
} else {
    // Handle the error if the file does not exist
    http_response_code(404);
    echo "File not found.";
}
?> -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist name</title>
    <link rel="stylesheet" href="static/css/main.css">
    <link rel="stylesheet" href="static/css/navbar.css">
    <link rel="stylesheet" href="static/css/button.css">
</head>

<body>
    <div id="navbar">
        <div id="navName">
            <h3>Artist Name</h3>
        </div>
        <div id="navButtons">
            <button class="navButton dark">click</button>
            <button class="navButton dark">click</button>
            <button class="navButton dark">click</button>
            <button class="navButton dark">click</button>
        </div>
    </div>

    <div id="main">
        <div id="announcement">
            <!-- Announcement Section -->
        </div>

        <div id="posts">
            <?php
            include 'db.php';

            $result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="post">';
                    echo '<h2>' . htmlspecialchars($row["title"]) . '</h2>';
                    echo '<p>' . nl2br(htmlspecialchars($row["content"])) . '</p>';
                    if (!empty($row["image_path"])) {
                        echo '<img src="' . htmlspecialchars($row["image_path"]) . '" alt="Post Image">';
                    }
                    echo '<p><small>Posted on ' . $row["created_at"] . '</small></p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No posts available.</p>';
            }

            $conn->close();
            ?>
        </div>
    </div>

</body>

</html>
