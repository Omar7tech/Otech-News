<?php
session_start();

if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require("db/db.php");

    $title = trim(htmlspecialchars(strip_tags(mysqli_real_escape_string($conn, $_POST['title']))));
    $content = trim(htmlspecialchars(strip_tags(mysqli_real_escape_string($conn, $_POST['content']))));
    $category = trim(htmlspecialchars(strip_tags(mysqli_real_escape_string($conn, $_POST['category']))));

    // Enforce a maximum limit of 10 characters for the category
    $category = substr($category, 0, 10);

    // If category is empty, set it to "other"
    if (empty($category)) {
        $category = "other";
    }

    $dateCreated = date('Y-m-d H:i:s'); // Current date and time

    if (empty($title) || empty($content)) {
        echo '<p style="color: red;">Please fill in all fields.</p>';
    } else {
        $query = "INSERT INTO posts (title, content, category, user_id, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        $userId = $_SESSION['id'];
        mysqli_stmt_bind_param($stmt, "sssis", $title, $content, $category, $userId, $dateCreated);
        
        if (mysqli_stmt_execute($stmt)) {
            echo '<p style="color: green;">Post added successfully!</p>';
        } else {
            echo '<p style="color: red;">Error adding post. Please try again later. Error: ' . mysqli_error($conn) . '</p>';
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Post</title>
    <link rel="stylesheet" href="style/editpost.css">
</head>

<body>
    <h1>Add Post</h1>

    <form action="addpost.php" method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="content">Content:</label>
        <textarea id="content" name="content" required style="resize: vertical;"></textarea>

        <label for="category">Category:</label>
        <input type="text" id="category" name="category">

        <input type="submit" value="Add Post">
        <a href="home.php"><button id="cancelbtn" type="button">Cancel</button></a>
    </form>
</body>

</html>