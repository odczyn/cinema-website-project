<?php

session_start();

require_once "connect.php";

$login = $_POST['login'];
$password = $_POST['password'];

$login = htmlentities($login, ENT_QUOTES, "UTF-8");
$password = htmlentities($password, ENT_QUOTES, "UTF-8");

$conn = @new mysqli($host, $db_user, $db_password, $db_name);

if($conn->connect_errno!=0){
    header("Location: ../error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
    exit();
}

$login = mysqli_real_escape_string($conn,$login);
$password = mysqli_real_escape_string($conn,$password);

$sql = "SELECT * FROM users WHERE login = '$login' AND password = '$password'";
if($result = $conn->query($sql)){
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $_SESSION['is_admin'] = $row['is_admin'];
        $_SESSION['user'] = $row['login'];
        $_SESSION['user_id'] = $row['id'];

        header("Location: ../index.php");
    }
    else{
        header("Location: ../register.php?error=no-account");
    }
}
else{
    header("Location: ../error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
}

?>