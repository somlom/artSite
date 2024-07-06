<?php
include 'db.php';
include 'helpers/admin.php';  // Include the functions file

// Handle post deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    deletePost($conn, $id);
}

// Handle post editing
$edit_post = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_post = editPost($conn, $edit_id);
}

// Handle post update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_post'])) {
    updatePost($conn, $_POST['post_id'], $_POST['title'], $_POST['content'], $_POST['category'], $_POST['existing_image'], $_FILES['image']);
}

// Handle new post submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['update_post'])) {
    addNewPost($conn, $_POST['title'], $_POST['content'], $_POST['category'], $_FILES['image']);
}

// Fetch all posts
$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Page - Manage Posts</title>
    <link rel="stylesheet" href="static/css/admin.css">
    <script src="static/js/admin.js"></script>
</head>

<body>
    <h1>Manage Posts</h1>

    <?php if (isset($edit_post)) : ?>
        <form method="post" action="admin.php" enctype="multipart/form-data">
            <h2>Edit Post</h2>

            <input type="hidden" name="post_id" value="<?php echo $edit_post['id']; ?>">

            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($edit_post['title']); ?>" required><br><br>

            <label for="content">Content:</label><br>
            <textarea id="content" name="content" rows="10" cols="30" required><?php echo htmlspecialchars($edit_post['content']); ?></textarea><br><br>

            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="Announcements" <?php if ($edit_post['category'] == 'Announcements') echo 'selected'; ?>>Announcements</option>
                <option value="Concerts" <?php if ($edit_post['category'] == 'Concerts') echo 'selected'; ?>>Concerts</option>
                <option value="Albums" <?php if ($edit_post['category'] == 'Albums') echo 'selected'; ?>>Albums</option>
            </select><br><br>

            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(this)"><br><br>
            <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($edit_post['image_path']); ?>">

            <?php if (!empty($edit_post['image_path'])) : ?>

                <img id="imagePreview" src="<?php echo htmlspecialchars($edit_post['image_path']); ?>" alt="Post Image" style="display: block;"><br>
                <span id="removeImage" onclick="removeImage()">Remove Image &#x274C;</span><br><br>

            <?php else : ?>

                <img id="imagePreview" src="" alt="Post Image"><br>
                <span id="removeImage" onclick="removeImage()">Remove Image &#x274C;</span><br><br>

            <?php endif; ?>

            <input type="submit" name="update_post" value="Update Post">

        </form>
    <?php else : ?>
        <form method="post" action="admin.php" enctype="multipart/form-data">
            <h2>Add New Post</h2>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required><br><br>
            </div>
            <div class="form-group">
                <label for="content">Content:</label><br>
                <textarea id="content" name="content" rows="10" cols="30" required></textarea><br><br>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <option value="Announcements">Announcements</option>
                    <option value="Concerts">Concerts</option>
                    <option value="Albums">Albums</option>
                </select><br><br>
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(this)"><br><br>
                <img id="imagePreview" src="" alt="Post Image"><br>
                <span id="removeImage" onclick="removeImage()">Remove Image &#x274C;</span><br><br>
            </div>
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
            <th>Category</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['content'])); ?></td>
                <td>
                    <?php if (!empty($row['image_path'])) : ?>
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Post Image" style="max-width: 100px; max-height: 100px;">
                    <?php endif; ?>
                </td>
                <td><?php echo $row['created_at']; ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td>
                    <a href="admin.php?edit=<?php echo $row['id']; ?>">Edit</a>
                    <a href="javascript:void(0);" onclick="confirmDeletion(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars(addslashes($row['title'])); ?>')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>