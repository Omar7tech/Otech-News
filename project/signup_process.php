<?php
require_once 'db/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $firstName = trim($_POST["firstname"]);
    $lastName = trim($_POST["lastname"]);
    $gender = $_POST["gender"];
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $errors = [];

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate first name
    if (empty($firstName)) {
        $errors[] = "First name is required.";
    }

    // Validate last name
    if (empty($lastName)) {
        $errors[] = "Last name is required.";
    }

    // Validate gender
    if (empty($gender)) {
        $errors[] = "Gender is required.";
    }

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        // Check if the email or username is already taken
        $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
        $checkUsernameQuery = "SELECT id FROM users WHERE username = ?";

        // Prepare and execute email query
        $stmtEmail = mysqli_prepare($conn, $checkEmailQuery);
        mysqli_stmt_bind_param($stmtEmail, "s", $email);
        mysqli_stmt_execute($stmtEmail);
        mysqli_stmt_store_result($stmtEmail);

        if (mysqli_stmt_num_rows($stmtEmail) > 0) {
            $errors[] = "Email is already taken.";
        }

        mysqli_stmt_close($stmtEmail);

        // Prepare and execute username query
        $stmtUsername = mysqli_prepare($conn, $checkUsernameQuery);
        mysqli_stmt_bind_param($stmtUsername, "s", $username);
        mysqli_stmt_execute($stmtUsername);
        mysqli_stmt_store_result($stmtUsername);

        if (mysqli_stmt_num_rows($stmtUsername) > 0) {
            $errors[] = "Username is already taken.";
        }

        mysqli_stmt_close($stmtUsername);

        if (empty($errors)) {
            // Insert user data into the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $signUpDate = date("Y-m-d H:i:s"); // Current date and time

            $insertQuery = "INSERT INTO users (email, first_name, last_name, gender, username, password, sign_up_date) VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmtInsert = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($stmtInsert, "sssssss", $email, $firstName, $lastName, $gender, $username, $hashedPassword, $signUpDate);

            if (mysqli_stmt_execute($stmtInsert)) {
                echo "success"; // You can customize the success message or redirect the user to the login page.
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmtInsert);
        } else {
            // Display validation errors
            echo implode("<br>", $errors);
        }
    } else {
        // Display validation errors
        echo implode("<br>", $errors);
    }

    mysqli_close($conn);
}
?>
