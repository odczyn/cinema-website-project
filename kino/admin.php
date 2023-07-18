<?php

    session_start();
    require_once 'backend/connect.php';

    if(!isset($_SESSION['user']) || !$_SESSION['is_admin']){
        header("Location: error.php?error=Brak%20uprawnien.");
        exit();
    }
    else{
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="utf-8"/>
        <title>Kino - Admin panel</title>
        <link rel="stylesheet" href="styles/style.css"/>
        <link rel="stylesheet" href="styles/admin.css"/>
    </head>
    <body style="filter:hue-rotate(270deg);">
        <div id="login-register-bar">
            <div id="login-box">
                <?php
                    if(!isset($_SESSION['user'])){
                ?>
                <form id="login-form" action="backend/login.php" method="POST">
                    <input type="text" placeholder="login" name="login"/>
                    <input type="password" placeholder="hasło" name="password"/>
                    <input type="submit" value="zaloguj"/>
                </form>
                <span id="register">
                    Nie masz konta? <a href="register.php">Zarejestruj się</a>
                </span>
                <?php
                    }else{
                        echo 'Zalogowano jako: '.$_SESSION['user'];
                ?>
                <form id="login-form" action="backend/logout.php" method="POST">
                    <input type="submit" value="wyloguj"/>
                </form>
                <?php } ?>
            </div>
        </div>
        <div id="header">
            <a href="index.php">
                <span id="logo">📽&#xFE0E;</span>
                <span id="title">KINO</span>
            </a>
        </div>
        <div id="menu">
            <div id="menu-inner">
                <a href="repertuar.php">REPERTUAR</a>
                <a href="harmonogram.php">HARMONOGRAM</a>
                <a href="onas.php">O NAS</a>
                <a href="kontakt.php">KONTAKT</a>
            </div>
        </div>
        <div id="container">
            <div id="form-row">
                <form class="admin-form" action="backend/addmovie.php" method="POST" enctype="multipart/form-data">
                    <h1>DODAWANIE FILMU</h1>
                    <label for="title-input">Tytuł:</label>
                    <input class="admin-input" id="title-input" type="text" name="title"/>
                    <label for="description-input">Opis:</label>
                    <textarea style="resize: none;" rows="4" class="admin-input" id="description-input" name="description"></textarea>
                    <label for="genre-input">Gatunek:</label>
                    <input class="admin-input" id="genre-input" type="text" name="genre"/>
                    <label for="length-input">Czas trwania:</label>
                    <input class="admin-input" id="length-input" type="number" step="10" name="length"/>
                    <label for="image-input">Zdjęcie:</label>
                    <input class="admin-input" id="image-input" type="file" name="image"/>
                    <input type="submit" value="Dodaj"/>
                </form>
                <form class="admin-form" action="backend/addscreening.php" method="POST">
                    <h1>DODAWANIE SEANSU</h1>
                    <label for="movie-input">Film:</label>
                    <select class="admin-input" id="movie-input" name="movie">
                        <?php
                            require_once "backend/connect.php";
                            $conn = @new mysqli($db_host, $db_user, $db_password, $db_name);
                            if($conn->connect_errno!=0){
                                header("Location: error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
                                exit();
                            }

                            $sql = "SELECT id, title FROM movies";
                            if($result = $conn->query($sql)){
                                while($row = $result->fetch_assoc()){
                                    $id = $row['id'];
                                    $title = $row['title'];
                                    echo "<option value='$id'>$title</option>";
                                }
                            }
                            else{
                                header("Location: error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
                                exit();
                            }
                        ?>
                    </select>
                    <label for="date-input">Data:</label>
                    <input class="admin-input" id="date-input" type="date" name="date"/>
                    <label for="time-input">Godzina:</label>
                    <input class="admin-input" id="time-input" type="time" name="time"/>
                    <span>
                        <input id="checkbox" type="checkbox" name="is_3D" />
                        3D?
                    </span>
                    <input type="submit" value="Dodaj"/>
                </form>
            </div>
            <?php
                $errorsMsgs = array(
                    'file-exists' => 'Plik o tej nazwie istnieje już na serwerze.',
                    'file-large' => 'Plik jest za duży.',
                    'wrong-format' => 'Plik jest w nieobsługiwanym formacie.',
                    'no-data' => 'Nie wypełniono wszystkich pól.'
                );

                if(!empty($_GET['error'])){
                    $errors = explode(',',$_GET['error']);
                    echo "<p style='color:red' id='return-msg'>";
                    for($i = 0;$i < count($errors);$i++)
                        echo $errorsMsgs[$errors[$i]].'<br>';
                    echo "</p>";
                }

                if(!empty($_GET['succ'])){
                    echo "<p style='color:green' id='return-msg'>Dodano.</p>";
                }
            ?>
        </div>
        <div id="footer">
            KINO by Iga Tupin 2021.
        </div>
    </body>
</html>
<?php
    }
?>