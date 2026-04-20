<?php
session_start();
include("connection/connect.php");
$message = "";

if(isset($_POST['submit'])) {
    // Validate inputs
    $username = trim($_POST['username']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $address = trim($_POST['address']);
    
    $errors = [];
    if(empty($username) || empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($password) || empty($address)) {
        $errors[] = "All fields are required";
    }
    if($password !== $cpassword) {
        $errors[] = "Passwords do not match";
    }
    if(strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    if(!preg_match('/^[0-9]{10,15}$/', $phone)) {
        $errors[] = "Invalid phone number";
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if(empty($errors)) {
        // Check username/email existence using prepared statements
        $stmt = $db->prepare("SELECT username FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) {
            $message = "Username or Email already exists!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $db->prepare("INSERT INTO users(username, f_name, l_name, email, phone, password, address) VALUES(?,?,?,?,?,?,?)");
            $insert->bind_param("sssssss", $username, $firstname, $lastname, $email, $phone, $hashed_password, $address);
            if($insert->execute()) {
                header("Location: login.php?registered=1");
                exit();
            } else {
                $message = "Registration failed, try again.";
            }
            $insert->close();
        }
        $stmt->close();
    } else {
        $message = implode("<br>", $errors);
    }
}
?>
<!-- HTML part unchanged -->
