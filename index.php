<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MikeTheYoungin</title>
    <link rel="stylesheet" href="static/css/main.css">
    <link rel="stylesheet" href="static/css/navbar.css">
    <link rel="stylesheet" href="static/css/button.css">
    <script src="static/js/navbar.js"></script>
</head>

<body>
    <div id="navbar">
        <div id="navName">
            <h3>MikeTheYoungin</h3>
        </div>
        <div id="navButtons">
            <?php
            include 'db.php';

            // Fetch distinct categories
            $categoryResult = $conn->query("SELECT DISTINCT category FROM posts");

            if ($categoryResult->num_rows > 0) {
                while ($categoryRow = $categoryResult->fetch_assoc()) {
                    $category = htmlspecialchars($categoryRow["category"]);
                    if (strlen($category) > 0) {
                        echo '<a href="#' . $category . '"><button class="navButton dark">' . $category . '</button></a>';
                    }
                }
            }
            ?>
        </div>
    </div>

    <div id="main">
        <div id="posts">
            <?php
            // Fetch posts and sort by category
            $postsByCategory = [];

            $result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $category = htmlspecialchars($row["category"]);
                    $postsByCategory[$category][] = $row;
                }
            }

            if (!empty($postsByCategory)) {
                foreach ($postsByCategory as $category => $posts) {
                    echo '<div id="' . $category . '" class="category">';
                    echo '<h2>' . $category . '</h2>';
                    foreach ($posts as $post) {
                        echo '<div class="post">';
                        echo '<h3>' . htmlspecialchars($post["title"]) . '</h3>';
                        echo '<p>' . nl2br(htmlspecialchars($post["content"])) . '</p>';
                        if (!empty($post["image_path"])) {
                            echo '<img src="' . htmlspecialchars($post["image_path"]) . '" alt="Post Image">';
                        }
                        echo '<p><small>Posted on ' . $post["created_at"] . '</small></p>';
                        echo '</div>';
                    }
                    if ($category == "Suggestions") {
                        echo '<button id="suggestButton" class="navButton dark" style="width: 100%;">Click to suggest me something</button>';
                    }
                    echo '</div>';
                }
            } else {
                echo '<p>No posts available.</p>';
            }

            $conn->close();
            ?>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("suggestButton").addEventListener("click", function() {
                var suggestion = prompt("Please suggest something:");
                if (suggestion) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "save_suggestion.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            alert("Your suggestion has been saved!");
                            // Optionally, you can update the page or perform other actions here
                        }
                    };
                    xhr.send("suggestion=" + encodeURIComponent(suggestion));
                }
            });
        });
    </script>
</body>

</html>