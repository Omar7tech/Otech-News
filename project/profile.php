<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("location:home.php");
    exit();
}

if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

require("db/db.php");
$username = $_SESSION['username'];
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

// Now you can use the fetched data as needed

// Example: Display user information

// ... (continue with other fields)

// Don't forget to close the statement

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile</title>
    <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/home.css">
    <link rel="stylesheet" href="style/profile.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

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
                            <?= $email ?>
                        </p>
                        <p>
                            date created :
                            <?= $sign_up_date ?>
                        </p>
                        <div class="buttons">
                            <a href="#demo-modal">
                                <button class="primary">edit</button>
                            </a>



                        </div>


                        <div id="demo-modal" class="modal">
                            <div class="modal__content">



                                <!-- ... (your HTML code above) -->
                                <div id="edit-form-container">
                                    <!-- Your form fields go here -->


                                    <form action="updatedata.php" method="post" enctype="multipart/form-data">
                                        <div>
                                            <h1>Edit</h1>
                                        </div>
                                        <div>
                                            <input type="email" name="email" required placeholder="email"
                                                value="<?= $email ?>">
                                        </div>
                                        <div>
                                            <input type="text" name="firstname" required placeholder="first name"
                                                value="<?= $first_name ?>">
                                        </div>
                                        <div>
                                            <input type="text" name="lastname" required placeholder="last name"
                                                value="<?= $last_name ?>">
                                        </div>
                                        <div>
                                            <select name="gender" required>
                                                <option value="" disabled hidden>Select Gender</option>
                                                <option value="male" <?= ($gender === 'male') ? 'selected' : '' ?>>Male
                                                </option>
                                                <option value="female" <?= ($gender === 'female') ? 'selected' : '' ?>>
                                                    Female
                                                </option>
                                            </select>
                                        </div>
                                        <div>
                                            <input type="text" name="username" required placeholder="username"
                                                value="<?= $username ?>">
                                        </div>
                                        <hr>
                                        <input type="file" name="image" id="image">
                                        <hr>
                                        <div>
                                            <button type="submit">Submit</button>
                                        </div>
                                        <!-- Add this div to display error messages -->
                                        <div>

                                            <!-- ... Your existing HTML code ... -->
                                            <!-- ... Your existing HTML code ... -->

                                            <div id="error-message" style="color: red;"></div>

                                            <script>
                                                document.querySelector("#demo-modal form").addEventListener("submit", function (event) {
                                                    event.preventDefault();

                                                    // Clear previous error messages
                                                    document.getElementById("error-message").innerHTML = "";

                                                    // Get form data
                                                    var formData = new FormData(this);

                                                    // Make the Ajax request
                                                    fetch('updatedata.php', {
                                                        method: 'POST',
                                                        body: formData,
                                                    })
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            if (data.success) {
                                                                // Data updated successfully, show a Toastify success message
                                                                Toastify({
                                                                    text: data.message,
                                                                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                                                                    className: "info",
                                                                }).showToast();
                                                                <?php

                                                                ?>



                                                                setTimeout(function () {
                                                                    // Redirect after 1 second
                                                                    window.location.href = "./profile.php";
                                                                }, 1000);
                                                            } else {
                                                                // Display the error message
                                                                document.getElementById("error-message").innerHTML = data.message;
                                                            }
                                                        })
                                                        .catch(error => {
                                                            console.error('Error:', error);
                                                        });
                                                });
                                            </script>
                                        </div>
                                    </form>
                                </div>





                                <a href="#" class="modal__close">&times;</a>
                            </div>
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