<?php

session_start();
require_once 'connect.php';

if(!isset($_SESSION['user']) || !$_SESSION['is_admin']){
    header("Location: ../error.php?error=Brak%20uprawnień.");
    exit();
}

$title = htmlentities($_POST['title'], ENT_QUOTES, "UTF-8");
$description = htmlentities($_POST['description'], ENT_QUOTES, "UTF-8");
$genre = htmlentities($_POST['genre'], ENT_QUOTES, "UTF-8");
$length = htmlentities($_POST['length'], ENT_QUOTES, "UTF-8");

$target_file = "images/".basename($_FILES["image"]["name"]);
$target_dir = "C:/xampp/htdocs/kino/".$target_file;
$imageFileType = strtolower(pathinfo($target_dir,PATHINFO_EXTENSION));

if(!isset($title) || !isset($description) || !isset($genre) || !isset($length)){
    header("Location: ../admin.php?error=no-data");
    exit();
}

if(file_exists($target_file)){
    header("Location: ../admin.php?error=file-exists");
    exit();
}
if ($_FILES["fileToUpload"]["size"] > 500000){
    header("Location: ../admin.php?error=file-large");
    exit();
}
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ){
    header("Location: ../admin.php?error=wrong-format");
    exit();
}

$conn = @new mysqli($db_host, $db_user, $db_password, $db_name);

if($conn->connect_errno!=0){
    header("Location: ../error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
    exit();
}

$title = mysqli_real_escape_string($conn,$title);
$description = mysqli_real_escape_string($conn,$description);
$genre = mysqli_real_escape_string($conn,$genre);
$length = mysqli_real_escape_string($conn,$length);

move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir);

$sql = "INSERT INTO movies VALUES(0,'$title','$description','$genre',$length,'$target_file')";
if($conn->query($sql)){
    header("Location: ../admin.php?succ=true");
}
else{
    header("Location: ../error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
}

?>