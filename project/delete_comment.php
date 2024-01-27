<?php
session_start();

if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

require("db/db.php");

// Check if comment_id is provided in the URL
if (!isset($_GET["comment_id"])) {
    header("Location: home.php"); // Redirect to home or another appropriate page
    exit();
}

$commentId = $_GET["comment_id"];
$userId = $_SESSION["id"];

// Check if the logged-in user owns the comment
$query = "SELECT user_id FROM comments WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $commentId);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $commentUserId);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if ($commentUserId !== $userId) {
    // If the logged-in user does not own the comment, redirect to home or another appropriate page
    header("Location: home.php");
    exit();
}

// Delete the comment
$deleteQuery = "DELETE FROM comments WHERE id = ?";
$deleteStmt = mysqli_prepare($conn, $deleteQuery);
mysqli_stmt_bind_param($deleteStmt, "i", $commentId);
mysqli_stmt_execute($deleteStmt);
mysqli_stmt_close($deleteStmt);

// Redirect to a page after successful deletion (you can change this to another page)
header("Location: mycomments.php");
exit();
?>
