<?php

session_start();

require_once 'connect.php';

if(!isset($_POST['row']) || !isset($_POST['column']) || !isset($_POST['screening_id']) || !isset($_SESSION['user'])){
    header("Location: ../error.php?error=Niepoprawne%20dane.");
    exit();
}

$row_no =  $_POST['row'];
$column_no = $_POST['column'];
$screening_id = $_POST['screening_id'];
$user_id = $_SESSION['user_id'];
$seat_id;

$conn = @new mysqli($host, $db_user, $db_password, $db_name);

if($conn->connect_errno!=0){
    header("Location: ../error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
    exit();
}

$sql = "SELECT id FROM seats WHERE row_no = $row_no AND column_no = $column_no";
if($result = $conn->query($sql)){
    if($result->num_rows == 0){
        header("Location: ../error.php?error=Nie%20ma%20takiego%20miejsca.");
        exit();
    }
    else{
        $row = $result->fetch_assoc();
        $seat_id = $row['id'];
    }
}
else{
    header("Location: ../error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
    exit();
}

$sql = "SELECT * FROM seats s JOIN tickets t ON t.seat_id = s.id WHERE s.row_no = $row_no AND s.column_no = $column_no AND t.screening_id = $screening_id";
if($result = $conn->query($sql)){
    if($result->num_rows != 0){
        header("Location: ../error.php?error=Miejsce%20zajęte.");
        exit();
    }
}
else{
    header("Location: ../error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
    exit();
}

$sql = "INSERT INTO tickets VALUES(0,$user_id,$screening_id,$seat_id)";
$conn->query($sql);

header("Location: ../booking.php?succ=1.");

?>