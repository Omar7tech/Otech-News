<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

// Check if post_id is provided in the URL
if (!isset($_GET["post_id"])) {
    header("Location: home.php");
    exit();
}

$post_id = $_GET["post_id"];

// Retrieve post information from the database based on post_id
require("db/db.php"); // Include your database connection code

$query = "SELECT * FROM posts WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if the post exists
if ($row = mysqli_fetch_assoc($result)) {
    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    // Redirect if the post doesn't exist
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add necessary meta tags, stylesheets, etc. -->
    <title>Edit Post</title>
    <link rel="stylesheet" href="style/editpost.css">
</head>
<body>
    <h1>Edit Post</h1>
    <form action="update_post.php" method="post">
        <!-- Populate the form with the current post data -->
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($row['title']) ?>" required>

        <label for="content">Content:</label>
        <textarea id="content" name="content" required style="resize: vertical;"><?= htmlspecialchars($row['content']) ?></textarea>

        <!-- Add other form fields as needed -->
        <label for="title">Title:</label>
        <input type="text" id="title" name="category" value="<?= htmlspecialchars($row['category']) ?>" required>

        <input type="hidden" name="post_id" value="<?= $post_id ?>">
        <input type="submit" value="Save Changes">
        <a href="myposts.php"><button id="cancelbtn" type="button">Cancel</button></a>
    </form>
</body>
</html>
