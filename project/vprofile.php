<?php
session_start();

if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}
if (!isset($_GET['user'])) {
    header("location:home.php");
    exit();
}
require("db/db.php");
$username = $_GET["user"];
$query = "SELECT   `email`, `first_name`, `last_name`, `gender`, `profile_picture`, `sign_up_date`
FROM `users`
WHERE `username` = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 0) {
    // No results, redirect
    header("location: home.php");
    exit();
}
mysqli_stmt_bind_result($stmt, $email, $first_name, $last_name, $gender, $profile_picture, $sign_up_date);
mysqli_stmt_fetch($stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile</title>
    <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/home.css">
    <link rel="stylesheet" href="style/vprofile.css">
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


                    <div class="card-container">

                        <img class="round" src="<?= $profile_picture ?>" alt="user" onerror="this.src='img/OIP.jpg'"
                            ; />
                        <h1>
                            <?= $first_name ?>
                            <?= $last_name ?>
                        </h1>
                        <h3>
                            <?= $username ?>
                        </h3>
                        <p>
                            Gender :
                            <?= $gender ?>
                        </p>

                        <p>
                            <?= $email ?>
                        </p>
                        <div class="buttons">
                            <a href="mailto:<?= $email ?>">
                                <button class="primary">Send Email</button>
                            </a>


                        </div>
                        <div class="skills">

                        </div>
                    </div>



                </div>
            </section>
        </div>
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


<?php
mysqli_stmt_close($stmt);
?>