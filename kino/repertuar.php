<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="utf-8"/>
        <title>Kino - Repertuar</title>
        <link rel="stylesheet" href="styles/style.css"/>
        <link rel="stylesheet" href="styles/repertuar.css"/>
    </head>
    <body>
        <div id="login-register-bar">
            <div id="login-box">
                <?php
                    session_start();
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
                <div id="login-form">
                    <?php if($_SESSION['is_admin']) { ?><form action="admin.php" method="GET"><input type="submit" value="admin"/></form><?php } ?>
                    <form  action="backend/logout.php" method="POST">
                        <input type="submit" value="wyloguj"/>
                    </form>
                </div>
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
        <?php
            require_once "backend/connect.php";
            $conn = @new mysqli($host, $db_user, $db_password, $db_name);
            if($conn->connect_errno!=0){
                header("Location: error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
                exit();
            }

            $sql = "SELECT * FROM movies ORDER BY id DESC";
            if($result = $conn->query($sql)){
                while($row = $result->fetch_assoc()){
                ?>
                    <a href="harmonogram.php?movie=<?php echo $row['id'] ?>">
                        <div class="movie-box">
                            <img alt="<?php echo "Plakat filmu ".$row['title'] ?>" src="<?php echo $row['image']; ?>"/>
                            <div class="movie-info">
                                <h2 class="movie-title"><?php echo $row['title']; ?></h2>
                                <p class="movie-description"><?php echo $row['description']; ?></p>
                                <span class="movie-genre"><?php echo $row['genre']." | ".$row['length']."min." ?></span>
                            </div>
                        </div>
                    </a>
                <?php
            }}
            else{
                header("Location: error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
                exit();
            }

        ?>
            
        </div>
        <div id="footer">
            KINO by Iga Tupin 2021.
        </div>
    </body>
</html>