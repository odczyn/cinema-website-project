<?php

session_start();
require_once 'connect.php';

if(!isset($_POST['movie']) || !isset($_POST['date']) || !isset($_POST['time'])){
    header("Location: ../admin.php?error=no-data");
    exit();
}

$movie = $_POST['movie'];
$datetime = strtotime($_POST['date'].' '.$_POST['time']);
$is_3D = 0;
if($_POST['is_3D'] == 'on')
    $is_3D = 1;

if(!isset($_SESSION['user']) || !$_SESSION['is_admin']){
    header("Location: ../error.php?error=Brak%20uprawnień.");
    exit();
}

$conn = @new mysqli($db_host, $db_user, $db_password, $db_name);

if($conn->connect_errno!=0){
    header("Location: ../error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
    exit();
}

$sql = "INSERT INTO screenings VALUES(0,'$movie',$datetime,$is_3D)";
if($conn->query($sql)){
    header("Location: ../admin.php?succ=true");
}
else{
    header("Location: ../error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
}

?>