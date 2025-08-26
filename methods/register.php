<?php
    // ini_set('display_errors', 1);
    // error_reporting(E_ALL);
    
    require_once './dbconnect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $fullName = $_POST['registerName'];
        $regNumber = $_POST['registerNumber'];
        $userPassword = $_POST['registerPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($userPassword !== $confirmPassword) {
            echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
            exit();
        }

        $safeFullName = mysqli_real_escape_string($conn, $fullName);
        $safeRegNumber = mysqli_real_escape_string($conn, $regNumber);

        $check_query = "SELECT user_registration_number FROM users WHERE user_registration_number = '$safeRegNumber'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            echo "<script>alert('This registration number is already in use.'); window.history.back();</script>";
            exit();
        }

        $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);
        $safeHashedPassword = mysqli_real_escape_string($conn, $hashedPassword);

        $userRole = "user";
        $sql = "INSERT INTO users (user_name, user_registration_number, user_password, user_role) VALUES ('$safeFullName', '$safeRegNumber', '$safeHashedPassword', '$userRole')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Registration successful!'); window.location.href = '../index.php';</script>";
        } else {
            echo "<script>alert('An error occurred: " . mysqli_error($conn) . "'); window.history.back();</script>";
        }
        mysqli_close($conn);
    }
?>