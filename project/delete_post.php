<?php
session_start();

if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["post_id"])) {
    header("Location: myposts.php");
    exit();
}

$post_id = $_GET["post_id"];

require("db/db.php");

$deleteCommentsQuery = "DELETE FROM comments WHERE post_id = ?";
$stmtComments = mysqli_prepare($conn, $deleteCommentsQuery);
mysqli_stmt_bind_param($stmtComments, "i", $post_id);

if (mysqli_stmt_execute($stmtComments)) {
    mysqli_stmt_close($stmtComments);

    $deletePostQuery = "DELETE FROM posts WHERE id = ?";
    $stmtPost = mysqli_prepare($conn, $deletePostQuery);
    mysqli_stmt_bind_param($stmtPost, "i", $post_id);

    if (mysqli_stmt_execute($stmtPost)) {
        mysqli_stmt_close($stmtPost);

        header("Location: myposts.php");
        exit();
    } else {
        echo "Error deleting post: " . mysqli_error($conn);
    }
} else {
    echo "Error deleting comments: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
