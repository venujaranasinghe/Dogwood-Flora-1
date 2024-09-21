<?php
    include 'connection.php';

    if(isset($_POST['submit-btn'])) {
        // Sanitize and escape user inputs
        $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $name = mysqli_real_escape_string($conn, $filter_name);

        $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $email = mysqli_real_escape_string($conn, $filter_email);

        $filter_password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = mysqli_real_escape_string($conn, $filter_password);

        $filter_cpassword = filter_var($_POST['cpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cpassword = mysqli_real_escape_string($conn, $filter_cpassword);

        // Check if the user already exists
        $select_user = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'") or die ('query failed');

        if(mysqli_num_rows($select_user) > 0) {
            $message[] = 'User already exists';
        } else {
            if($password != $cpassword) {
                $message[] = 'Passwords do not match';
            } else {
                // Hash the password before storing it
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert new user into the database
                mysqli_query($conn, "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')") or die('query failed');

                $message[] = 'Registration successful';
                header('location:login.php');
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>User Registration Page</title>
</head>
<body>
<?php 
if (isset($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>'.$msg.'</span>
            <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
        </div>
        ';
    }
}
?>

    <section class="form-container">
    
        <form action="" method="post">
            <h3>register now</h3>
            <section class="social-icons">
                <a href="#" class="icon"><i class="fa-brands fa-google"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-facebook"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
            </section>
            <input type="text" name="name" placeholder="enter your name" required>
            <input type="email" name="email" placeholder="enter your email" required>
            <input type="password" name="password" placeholder="enter your password" required>
            <input type="password" name="cpassword" placeholder="re-enter your password" required>
            <input type="submit" name="submit-btn" class="btn" value="register now">
            <p>
                Already have an account ? <a href="login.php">login now</a>
            </p>
        </form>
    </section>    
</body>
</html>