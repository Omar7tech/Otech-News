<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style/auth.css">

</head>

<body>
    <div class="container mt-4 mb-3">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-md-6">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="card px-4 py-5">
                        <h1>Log In</h1>
                        <h5 class="mt-3">OTech News<br> Disscuss Technial News</h5>
                        <small class="mt-2 text-muted">Your Source for Cutting-Edge Tech News!</small>
                        <hr>
                        <div class="form-input">
                            <i class="fa fa-envelope"></i>
                            <input type="email" name="email" class="form-control" placeholder="Email address" required
                                autocomplete="off">
                        </div>
                        <div class="form-input">
                            <i class="fa fa-lock"></i>
                            <input type="password" name="password" class="form-control" placeholder="password" required
                                autocomplete="new-password">
                        </div>
                        <button class="glow-on-hover" type="submit" name="submit" value="submit">Log In</button>
                        <div class="text-center mt-3">
                            <div class="text-center mt-3">
                                <span class="error-message" style="color: red;"></span>
                            </div>
                            <div class="text-center mt-4">
                                <span>not a member ?</span>
                                <a href="signup.php" class="text-decoration-none">Sign Up</a>
                                <br>
                                <a href="index.html" class="text-decoration-none">Home</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("form").submit(function (event) {
                event.preventDefault(); // Prevent the default form submission

                var formData = $(this).serialize();

                $.ajax({
                    type: "POST",
                    url: "login_process.php", // Update the URL to your backend processing file
                    data: formData,
                    success: function (response) {
                        if (response === "success") {
                            // Redirect or perform other actions upon successful login
                            window.location.href = "home.php";
                        } else {
                            $(".error-message").html(response);
                        }
                    },
                    error: function () {
                        $(".error-message").html("Oops! Something went wrong. Please try again later.");
                    }
                });
            });
        });

    </script>
</body>

</html>

<?php
require_once 'db/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $errors = [];

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        // Check credentials against the database
        $sql = "SELECT id, username, password FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Start a session and store necessary data
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // Redirect to the desired page upon successful login
                            echo "success";
                        } else {
                            echo "Incorrect email or password.";
                        }
                    }
                } else {
                    echo "Incorrect email or password.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_close($conn);
    } else {
        // Display validation errors
        echo implode("<br>", $errors);
    }
}
?>