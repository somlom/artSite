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

    // Handle image upload if a new image is provided
    if ($image_file && $image_file['error'] == 0) {
        $image_path = 'uploads/' . basename($image_file['name']);
        move_uploaded_file($image_file['tmp_name'], $image_path);
    } else {
        $image_path = $conn->real_escape_string($existing_image);
    }

    $conn->query("UPDATE posts SET title='$title', content='$content', category='$category', image_path='$image_path' WHERE id=$id");
    header("Location: admin.php");
    exit;
}

function addNewPost($conn, $title, $content, $category, $image_file)
{
    $title = $conn->real_escape_string($title);
    $content = $conn->real_escape_string($content);
    $category = $conn->real_escape_string($category);

    // Handle image upload
    if ($image_file && $image_file['error'] == 0) {
        $image_path = 'uploads/' . basename($image_file['name']);
        move_uploaded_file($image_file['tmp_name'], $image_path);
    } else {
        $image_path = '';
    }

    $conn->query("INSERT INTO posts (title, content, category, image_path) VALUES ('$title', '$content', '$category', '$image_path')");
    header("Location: admin.php");
    exit;
}

function acceptSuggestion($conn, $suggestion_id)
{
    $suggestion_id = intval($suggestion_id);
    $suggestion_result = $conn->query("SELECT * FROM suggestions WHERE id=$suggestion_id");
    if ($suggestion_row = $suggestion_result->fetch_assoc()) {
        $title = 'Suggestion';
        $content = $conn->real_escape_string($suggestion_row['suggestion']);
        $category = 'Suggestions';
        $image_path = ''; // No image for suggestions

        // Insert the suggestion into posts table
        $conn->query("INSERT INTO posts (title, content, category, image_path) VALUES ('$title', '$content', '$category', '$image_path')");

        // Delete the suggestion from suggestions table
        $conn->query("DELETE FROM suggestions WHERE id=$suggestion_id");

        header("Location: admin.php");
        exit;
    }
}

function deleteSuggestion($conn, $suggestion_id)
{
    $suggestion_id = intval($suggestion_id);
    $conn->query("DELETE FROM suggestions WHERE id=$suggestion_id");
    header("Location: admin.php");
    exit;
}
