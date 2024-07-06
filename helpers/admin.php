<?php
include 'db.php';

function deletePost($conn, $id)
{
    $id = intval($id);
    $conn->query("DELETE FROM posts WHERE id=$id");
    header("Location: admin.php");
    exit;
}

function editPost($conn, $edit_id)
{
    $edit_id = intval($edit_id);
    $edit_result = $conn->query("SELECT * FROM posts WHERE id=$edit_id");
    return $edit_result->fetch_assoc();
}

function updatePost($conn, $id, $title, $content, $category, $existing_image, $image_file)
{
    $id = intval($id);
    $title = $conn->real_escape_string($title);
    $content = $conn->real_escape_string($content);
    $category = $conn->real_escape_string($category);

    $image_path = $existing_image;
    if (!empty($image_file['name'])) {
        $target_dir = "static/pic/";
        $image_name = basename($image_file['name']);
        $target_file = $target_dir . time() . "_" . $image_name;

        if (move_uploaded_file($image_file['tmp_name'], $target_file)) {
            $image_path = $conn->real_escape_string($target_file);
        }
    }

    $conn->query("UPDATE posts SET title='$title', content='$content', image_path='$image_path', category='$category' WHERE id=$id");
    header("Location: admin.php");
    exit;
}

function addNewPost($conn, $title, $content, $category, $image_file)
{
    $title = $conn->real_escape_string($title);
    $content = $conn->real_escape_string($content);
    $category = $conn->real_escape_string($category);

    $image_path = '';
    if (!empty($image_file['name'])) {
        $target_dir = "static/pic/";
        $image_name = basename($image_file['name']);
        $target_file = $target_dir . time() . "_" . $image_name;

        if (move_uploaded_file($image_file['tmp_name'], $target_file)) {
            $image_path = $conn->real_escape_string($target_file);
        }
    }

    $conn->query("INSERT INTO posts (title, content, image_path, category) VALUES ('$title', '$content', '$image_path', '$category')");
    header("Location: admin.php");
    exit;
}
