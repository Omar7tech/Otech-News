<?php
session_start();
if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit(); 
}

require("db/db.php");
$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Comments</title>
    <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/home.css">
</head>

<body>
    <div class="app-container">
        <?php require("partials/leftarea.php"); ?>
        <div class="main-area">
            <div class="main-area-header" style="overflow: auto;">
                <button class="btn-show-left-area">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </button>
            </div>
            <div class="userd">
                <!-- Existing code ... -->

                <img class="round" src="<?= getProfilePicture($username) ?>" alt="user"
                    onerror="this.src='img/OIP.jpg'" />

                <!-- Existing code ... -->

                <?php
                // Function to get the profile picture path for a given username
                function getProfilePicture($username)
                {
                    require("db/db.php");

                    // Query to fetch profile picture path based on the username
                    $query = "SELECT profile_picture FROM users WHERE username = ?";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, "s", $username);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);

                    // If the user is found, fetch the profile picture path
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        mysqli_stmt_bind_result($stmt, $profilePicture);
                        mysqli_stmt_fetch($stmt);

                        // Return the profile picture path
                        return $profilePicture;
                    } else {
                        // Default image path if the user is not found
                        return 'img/default-profile-picture.jpg';
                    }

                    // Don't forget to close the statement
                    
                }
                ?>

                <h1>
                    <?= $username ?>
                </h1>
            </div>

            <br>
            <hr>
            <section class="content-section" style="margin-bottom: 200px;">
                <div class="page">
                    <div class="archive">
                        <?php
                        $userId = $_SESSION['id'];
                        $query = "SELECT comments.*, posts.title AS post_title
                            FROM comments
                            INNER JOIN posts ON comments.post_id = posts.id
                            WHERE comments.user_id = $userId";

                        $result = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_assoc($result)):
                            ?>
                            <article class="article">
                                <div class="blog-container">
                                    <div class="blog-header">
                                        <div class="blog-author--no-cover">
                                            <a href="delete_comment.php?comment_id=<?= $row['id'] ?>">Delete</a>
                                            <a href="view_post.php?post_id=<?= $row['post_id'] ?>">
                                                <h3><?= $row['post_title'] ?></h3>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="blog-body">
                                        <div class="blog-summary">
                                            <p><?= $row["content"] ?></p>
                                        </div>
                                    </div>
                                    <div class="blog-footer">
                                        <ul>
                                            <li class="published-date"><?= $row['created_at'] ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </article>
                            <?php
                        endwhile;
                        ?>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        // Existing JavaScript code...
    </script>
</body>

</html>
