<?php
session_start();
require("db/db.php");

// Validate session
if (!isset($_SESSION["username"])) {
    $response = array('success' => false, 'message' => 'User not authenticated.');
    sendResponse($response);
}
$username = $_SESSION['username'];
// Validate form data
$email = $_POST['email'];
$firstName = $_POST['firstname'];
$lastName = $_POST['lastname'];
$gender = $_POST['gender'];
$newUsername = $_POST['username'];

if (empty($email) || empty($firstName) || empty($lastName) || empty($gender) || empty($newUsername)) {
    $response = array('success' => false, 'message' => 'Please fill in all fields.');
    sendResponse($response);
}

// Check if email already exists
$query = "SELECT * FROM users WHERE email = ? AND username != ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $email, $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    // Email already exists
    $response = array('success' => false, 'message' => 'Email already exists.');
    sendResponse($response);
}
// Check if username already exists
$query = "SELECT * FROM users WHERE username = ? AND username != ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $newUsername, $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) > 0) {
    // Username already exists
    $response = array('success' => false, 'message' => 'Username already exists.');
    sendResponse($response);
}
// Check if an image is uploaded
if (isset($_FILES['image'])) {
    $file = $_FILES['image'];

    // Check for errors during file upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response = array('success' => false, 'message' => 'Error uploading image.');
        sendResponse($response);
    }
    // Move the uploaded file to a specific directory
    $uploadDir = 'img/'; // Assuming the "img" folder is in the project directory
    $uploadPath = $uploadDir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        // Image uploaded successfully, update user data
        $query = "UPDATE users SET email=?, first_name=?, last_name=?, gender=?, username=?, profile_picture=? WHERE username=?";
        $stmt = mysqli_prepare($conn, $query);
        $profilePicture = 'img/' . basename($file['name']); // Adjust this path accordingly
        mysqli_stmt_bind_param($stmt, "sssssss", $email, $firstName, $lastName, $gender, $newUsername, $profilePicture, $username);
        $result = mysqli_stmt_execute($stmt);
        if ($result) {
            // Update session username
            $_SESSION['username'] = $newUsername;
            $response = array('success' => true, 'message' => 'Data updated successfully.');
        } else {
            $response = array('success' => false, 'message' => 'Error updating data.');
        }
    } else {
        // Error moving the uploaded file
        $response = array('success' => false, 'message' => 'Error moving uploaded image.');
    }
} else {
    // No image uploaded
    $response = array('success' => false, 'message' => 'Please upload an image.');
}
sendResponse($response);
function sendResponse($response)
{
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>