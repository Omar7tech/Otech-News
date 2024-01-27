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
    <div class="container mt-4 mb-5">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-md-6">
                <form action="../handler/signup.php" method="post">
                    <div class="card px-4 py-5">
                        <center>
                            <h1 style="margin-top: -25px; margin-bottom: 20px;">Sign Up</h1>
                        </center>
                        <small class="mt-2 text-muted"></small>
                        <div class="form-input">
                            <i class="fa fa-envelope"></i>
                            <input type="email" class="form-control" placeholder="Email address" autocomplete="off" required
                                name="email">
                        </div>
                        <div class="form-input">
                            <i class="fa fa-circle"></i>
                            <input type="text" class="form-control" placeholder="First name" autocomplete="off" required
                                name="firstname">
                        </div>
                        <div class="form-input">
                            <i class="fa fa-circle"></i>
                            <input type="text" name="lastname" class="form-control" placeholder="Last name"required
                                autocomplete="off">
                        </div>
                        <div class="form-input">
                            <i class="fa fa-circle"></i>
                            <select class="form-select form-control" aria-label="Default select example" name="gender"required
                                required>
                                <option value="" disabled="" selected="" hidden="">Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <div class="form-input">
                            <i class="fa fa-user"></i>
                            <input type="text" name="username" class="form-control" placeholder="User name"required
                                autocomplete="off">
                        </div>

                        <div class="form-input">
                            <i class="fa fa-lock"></i>
                            <input type="password" name="password" class="form-control" placeholder="password"required
                                autocomplete="new-password">
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" required>
                            <label class="form-check-label" for="flexCheckChecked">
                                I agree all the statements
                            </label>
                        </div>
                        <button class="btn btn-primary mt-4 signup" type="submit">Sign Up</button>
                        <!-- Add this div to display error messages -->
                        <div class="text-center mt-3">
                            <span class="error-message" style="color: red;"></span>
                        </div>

                        <div class="text-center mt-4">
                            <span>Already a member?</span>
                            <a href="login.php" class="text-decoration-none">Log In</a><br>
                            <a href="index.html" class="text-decoration-none">Home</a>
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
                    url: "signup_process.php", // Update the URL to your backend processing file
                    data: formData,
                    success: function (response) {
                        if (response === "success") {
                            // Redirect or perform other actions upon successful signup
                            window.location.href = "login.php";
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