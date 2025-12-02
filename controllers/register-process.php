<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['fullname'])) {

        // Database Connection
        require 'includes/dbconnection.php';

        // Collecting Form Data
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Check that all fields are filled
        if (!empty($fullname) && !empty($email) && !empty($username) && !empty($password) && !empty($confirm_password)) {
            if ($password === $confirm_password) {
                $sql_query = "INSERT INTO users (fullname, email, username, user_password) 
                      VALUES ('$fullname', '$email', '$username', '$password')";

                if ($con->query($sql_query) === TRUE) {
                    echo "<script>alert('Registration Successfull... Please, Login to Continue.');
                    window.location.href = '../login.php';</script>";
                } else {
                    echo "<script>alert('ERROR: Registration Failed!!!');</script>";
                    echo "Error: " . $con->error;
                }
            } else {
                echo "<script>alert('ERROR: Passwords does not match!!');</script>";
            }
        } else {
            echo "<script>alert('ERROR: Please, Fill all the Details.');</script>";
        }

        $con->close();
    }
}
