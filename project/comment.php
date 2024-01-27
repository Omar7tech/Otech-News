<?php
session_start();

if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

require("db/db.php");

// Function to dump and die for debugging
function dd($value)
{
    echo ('<pre>');
    var_dump($value);
    echo ('</pre>');
    die();
}

// Check if post_id is provided in the URL
if (!isset($_GET["post"])) {
    header("Location: home.php");
    exit();
}

$postId = mysqli_real_escape_string($conn, $_GET['post']);
$postQuery = "SELECT posts.*, users.username
              FROM posts
              INNER JOIN users ON posts.user_id = users.id
              WHERE posts.id = $postId";
$postResult = mysqli_query($conn, $postQuery);

if ($postResult) {
    $post = mysqli_fetch_assoc($postResult);

    // Check if the post exists
    if ($post) {
        // Get comments for the post
        $commentQuery = "SELECT comments.*, users.username, users.email, users.profile_picture
                         FROM comments
                         INNER JOIN users ON comments.user_id = users.id
                         WHERE comments.post_id = $postId";

        $commentResult = mysqli_query($conn, $commentQuery);

        if ($commentResult) {
            $comments = [];

            while ($row = mysqli_fetch_assoc($commentResult)) {
                $comments[] = $row;
            }
        } else {
            // Handle the comments query error here.
            echo "Error: " . mysqli_error($conn);
        }

        // Handle comment submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["comment_content"])) {
                $commentContent = mysqli_real_escape_string($conn, $_POST["comment_content"]);
                $userId = $_SESSION["id"];

                // Insert the comment into the database
                $insertCommentQuery = "INSERT INTO comments (user_id, post_id, content, created_at)
                                       VALUES ($userId, $postId, '$commentContent', NOW())";

                $insertCommentResult = mysqli_query($conn, $insertCommentQuery);

                if ($insertCommentResult) {
                    // Comment inserted successfully, refresh the page or handle as needed
                    header("Location: $_SERVER[PHP_SELF]?post=$postId");
                    exit();
                } else {
                    // Handle the comment insertion error here.
                    echo "Error: " . mysqli_error($conn);
                }
            }
        }
    } else {
        // Handle the case where the post doesn't exist.
        die();
    }
} else {
    // Handle the post query error here.
    echo "Error: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Add necessary meta tags, stylesheets, etc. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/comm.css">
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
            <section class="content-section">
                <div class="page">
                    <ol class="timeline">
                        <!-- Post item -->
                        <li class="timeline-item | extra-space">
                            <div class="timeline-item-wrapper">
                                <div class="timeline-item-description">
                                    <i class="avatar | small">
                                        <img src="https://assets.codepen.io/285131/hat-man.png" />
                                    </i>
                                    <span><a href="#">
                                            <?= $post['username'] ?>
                                        </a></span>
                                </div>
                                <div class="comment" id="comment">
                                    <h1>
                                        <?= $post['title'] ?>
                                    </h1>
                                    <p>
                                        <?= $post['content'] ?>
                                    </p>
                                </div>
                            </div>
                        </li>

                        <!-- Comment form -->
                        <li class="timeline-item">
                            <span class="timeline-item-icon | avatar-icon">
                                <i class="avatar">
                                    <img src="https://assets.codepen.io/285131/hat-man.png" />
                                </i>
                            </span>
                            <form action="<?= $_SERVER['PHP_SELF'] ?>?post=<?= $postId ?>" method="post">
                                <div class="new-comment">
                                    <input type="text" name="comment_content" placeholder="Add a comment..." />
                                    <button type="submit">Submit</button>
                                </div>
                            </form>
                        </li>

                        <!-- Comments loop -->
                        <?php foreach ($comments as $comment): ?>
                            <li class="timeline-item | extra-space">
                                <span class="timeline-item-icon | filled-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                        <path fill="none" d="M0 0h24v24H0z" />
                                        <path fill="currentColor"
                                            d="M6.455 19L2 22.5V4a1 1 0 0 1 1-1h18a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H6.455zM7 10v2h2v-2H7zm4 0v2h2v-2h-2zm4 0v2h2v-2h-2z" />
                                    </svg>
                                </span>
                                <div class="timeline-item-wrapper">
                                    <div class="timeline-item-description">
                                        <i class="avatar | small">
                                            <img class="round" src="<?= $comment['profile_picture'] ?>" alt="user"
                                                onerror="this.src='img/OIP.jpg'" />
                                        </i>
                                        <span><a href="./vprofile.php?user=<?= $comment['username'] ?>">
                                                <?= $comment['username'] ?>
                                            </a> commented on
                                            <?= $comment['created_at'] ?>
                                        </span>
                                    </div>
                                    <div class="comment">
                                        <p>
                                            <?= $comment['content'] ?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                        <!-- End Comments loop -->
                    </ol>
                </div>
            </section>
        </div>
        <a href="home.php">
            <div id="goBackBtn">
                Go Back
            </div>
        </a>
    </div>
    
    <script>
        document.querySelector(".btn-show-left-area").addEventListener("click", function () {
            var leftArea = document.querySelector(".left-area");
            leftArea.classList.remove("show");
            leftArea.classList.add("show");
        });
        document.querySelector(".btn-close-left").addEventListener("click", function () {
            var leftArea = document.querySelector(".left-area");
            leftArea.classList.remove("show");
        });

        var mainArea = document.querySelector('.main-area');
        mainArea.addEventListener('scroll', function () {
            if (mainArea.scrollTop >= 88) {
                document.querySelector('div.main-area-header').classList.add('fixed');
            } else {
                document.querySelector('div.main-area-header').classList.remove('fixed');
            }
        });
    </script>
</body>

</html>