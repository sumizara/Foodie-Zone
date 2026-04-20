<?php
session_start();
include("connection/connect.php");

$message = "";
if(isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if(!empty($username) && !empty($password)) {
        // Use prepared statement
        $stmt = $db->prepare("SELECT u_id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($row = $result->fetch_assoc()) {
            if(password_verify($password, $row['password'])) {
                $_SESSION["user_id"] = $row['u_id'];
                header("Location: index.php");
                exit();
            } else {
                $message = "Invalid Username or Password!";
            }
        } else {
            $message = "Invalid Username or Password!";
        }
        $stmt->close();
    } else {
        $message = "Please fill all fields";
    }
}
?>
<!-- Rest of HTML remains same but update the form action to "" -->
