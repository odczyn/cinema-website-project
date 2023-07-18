<?php

session_start();

require_once "connect.php";

$login = $_POST['login'];
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$agreement = $_POST['agreement'];

$login = htmlentities($login, ENT_QUOTES, "UTF-8");
$password1 = htmlentities($password1, ENT_QUOTES, "UTF-8");
$password2 = htmlentities($password2, ENT_QUOTES, "UTF-8");

$errors = "";

if($login == "")
    $errors = $errors.'login-empty,';
if($password1 == "")
    $errors = $errors.'password-empty,';
if($password1 != $password2)
    $errors = $errors.'different-passwords,';
if($agreement != 'on')
    $errors = $errors.'agreement-empty,';
if(strlen($login) < 4 || strlen($login) > 20)
    $errors = $errors.'login-short,';
if(strlen($password1) < 6 || strlen($password1) > 20)
    $errors = $errors.'password-short,';

if(strlen($errors) > 0)
{
    $errors = substr($errors,0,strlen($errors)-1);
    header("Location: ../register.php?error=$errors");
    exit();
}

$conn = @new mysqli($host, $db_user, $db_password, $db_name);

if($conn->connect_errno!=0){
    header("Location: ../error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
    exit();
}

$login = mysqli_real_escape_string($conn,$login);
$password1 = mysqli_real_escape_string($conn,$password1);

$sql = "SELECT * FROM users WHERE login = '$login'";
if($result = @$conn->query($sql)){
    if($result->num_rows > 0){
        header('Location: ../register.php?error=login-taken');
        exit();
    }
}
else{
    header("Location: ../error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
    exit();
}

$sql = "INSERT INTO users values(0, '$login', '$password1', 0)";
if(@$conn->query($sql)){
    $_SESSION['user'] = $login;
}
else{
    header("Location: ../error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
    exit();
}

$sql = "SELECT id FROM users WHERE login = '$login'";
if($result = $conn->query($sql)){
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['is_admin'] = 0;
    header("Location: ../register.php?succ=1");
}
else{
    header("Location: ../error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
    exit();
}
?>