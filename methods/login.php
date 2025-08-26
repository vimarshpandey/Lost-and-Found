<?php
    session_start();

    require_once './dbconnect.php';

    if($_SERVER["REQUEST_METHOD"] == "POST") {

        $loginNumber = $_POST['loginNumber'];
        $loginPassword = $_POST['loginPassword'];

        $safeloginNumber = mysqli_real_escape_string($conn, $loginNumber);

        $query = "SELECT * FROM users WHERE user_registration_number = '$safeloginNumber'";
        $result = mysqli_query($conn, $query);

        if($result && mysqli_num_rows($result) == 1) {

            $user = mysqli_fetch_assoc($result);

            if(password_verify($loginPassword, $user['user_password'])) {
                
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['user_registration_number'];
                $_SESSION['user_name'] = $user['user_name'];

                header("Location: ./dashboard.php");
                exit;

            } else {
                echo "<script>alert('Invalid Password. Please try again'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('User not Found. Please check your Registration Number.'); window.history.back();</script>";
        }
        mysqli_close($conn);
    }
?>
