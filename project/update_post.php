<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

require("db/db.php"); // Make sure to include your database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve post_id and other form data
    $post_id = $_POST["post_id"];
    $new_title = $_POST["title"];
    $new_content = $_POST["content"];
    $category = $_POST["category"];

    // Update the post in the database based on post_id
    $query = "UPDATE posts SET title = ?, content = ? ,category = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $new_title, $new_content,$category, $post_id);

    if (mysqli_stmt_execute($stmt)) {
        // Successfully updated, redirect to home.php or wherever needed
        header("Location: home.php");
        exit();
    } else {
        // Handle the case where the update fails
        echo "Update failed: " . mysqli_stmt_error($stmt);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    // Redirect if the form is not submitted
    header("Location: home.php");
    exit();
}
