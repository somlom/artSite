<?php
include 'db.php';

// Handle post deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM posts WHERE id=$id");
    header("Location: admin.php");
    exit;
}

// Handle post editing
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_result = $conn->query("SELECT * FROM posts WHERE id=$edit_id");
    $edit_post = $edit_result->fetch_assoc();
}

// Handle post update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_post'])) {
    $id = intval($_POST['post_id']);
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);

    // Handle file upload
    $image_path = $_POST['existing_image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_name = basename($_FILES['image']['name']);
        $target_dir = "static/pic/";
        $target_file = $target_dir . time() . "_" . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = $conn->real_escape_string($target_file);
        }
    }

    $conn->query("UPDATE posts SET title='$title', content='$content', image_path='$image_path' WHERE id=$id");
    header("Location: admin.php");
    exit;
}

// Handle new post submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['update_post'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);

    // Handle file upload
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_name = basename($_FILES['image']['name']);
        $target_dir = "static/pic/";
        $target_file = $target_dir . time() . "_" . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = $conn->real_escape_string($target_file);
        }
    }

    $conn->query("INSERT INTO posts (title, content, image_path) VALUES ('$title', '$content', '$image_path')");
    header("Location: admin.php");
    exit;
}

// Fetch all posts
$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Page - Manage Posts</title>
    <script>
        function confirmDeletion(postId, postTitle) {
            var userInput = prompt("To confirm deletion, please type the post title:");
            if (userInput === postTitle) {
                window.location.href = 'admin.php?delete=' + postId;
            } else {
                alert("Post title does not match. Deletion cancelled.");
            }
        }
    </script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Manage Posts</h1>

    <?php if (isset($edit_post)): ?>
        <form method="post" action="admin.php" enctype="multipart/form-data">
            <h2>Edit Post</h2>
            <input type="hidden" name="post_id" value="<?php echo $edit_post['id']; ?>">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($edit_post['title']); ?>" required><br><br>
            <label for="content">Content:</label><br>
            <textarea id="content" name="content" rows="10" cols="30" required><?php echo htmlspecialchars($edit_post['content']); ?></textarea><br><br>
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*"><br><br>
            <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($edit_post['image_path']); ?>">
            <?php if (!empty($edit_post['image_path'])): ?>
                <img src="<?php echo htmlspecialchars($edit_post['image_path']); ?>" alt="Post Image" style="max-width: 100px; max-height: 100px;"><br><br>
            <?php endif; ?>
            <input type="submit" name="update_post" value="Update Post">
        </form>
    <?php else: ?>
        <form method="post" action="admin.php" enctype="multipart/form-data">
            <h2>Add New Post</h2>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required><br><br>
            <label for="content">Content:</label><br>
            <textarea id="content" name="content" rows="10" cols="30" required></textarea><br><br>
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*"><br><br>
            <input type="submit" value="Add Post">
        </form>
    <?php endif; ?>

    <h2>All Posts</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Image</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['content'])); ?></td>
                <td>
                    <?php if (!empty($row['image_path'])): ?>
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Post Image" style="max-width: 100px; max-height: 100px;">
                    <?php endif; ?>
                </td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <a href="admin.php?edit=<?php echo $row['id']; ?>">Edit</a>
                    <!-- <a href="admin.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a> -->
                    <a href="javascript:void(0);" onclick="confirmDeletion(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars(addslashes($row['title'])); ?>')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
