<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {

        require '../includes/dbconnection.php';

        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $admin_email = 'admin@moviemate.com';
        $admin_password = 'adminPassword';

        if ($email === $admin_email && $password === $admin_password) {
            $_SESSION['adminname'] = $admin_email;

            echo "<script>
                    alert('Welcome to Admin Dashboard.');
                    window.location.href = '../admin/index.php';
                  </script>";
            exit();
        }

        $sql_query = "SELECT * FROM users WHERE email = '$email' AND user_password = '$password'";
        $result = mysqli_query($con, $sql_query);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            $_SESSION['user_id']  = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email']    = $row['email'];

            if (isset($_POST['remember'])) {
                setcookie('email', $email, time() + (7 * 24 * 60 * 60), "/");
            }

            $_SESSION['popup_type'] = "login_success";
            header("Location: ../index.php");
            exit();
        } else {
            echo "<script>
                    alert('Invalid Email or Password!');
                    window.location.href = '../login.php';
                  </script>";
            exit();
        }

        $con->close();
    }
}
?>
