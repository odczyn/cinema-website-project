<?php

require_once "backend/connect.php";
$conn = @new mysqli($host, $db_user, $db_password, $db_name);
if($conn->connect_errno!=0){
    header("Location: error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
    exit();
}

$t = strtotime(date('d-m-Y'));
for($i=0;$i<1000;$i++)
{
    while(date('H', $t)<10 || date('H', $t)>21){
        $t = $t + rand(150*60,700*60);
    }
    $movie_id = rand(3,9);
    $is_3D = rand(0,1);
    $sql = "INSERT INTO screenings VALUES(0,$movie_id,$t,$is_3D)";
    $conn->query($sql);
    $t = $t + rand(150*60,700*60);
}

?>