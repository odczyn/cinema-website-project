<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="utf-8"/>
        <title>Kino - Rezerwacja</title>
        <link rel="stylesheet" href="styles/style.css"/>
        <link rel="stylesheet" href="styles/booking.css"/>
        <script src="scripts/canvas.js"></script>
        <script>
        <?php
            if(!isset($_GET['succ']))
            {
                require_once "backend/connect.php";

                $conn = @new mysqli($host, $db_user, $db_password, $db_name);

                if($conn->connect_errno!=0){
                    header("Location: error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
                    exit();
                }

                $id = $_GET['id'];

                $sql = "SELECT time FROM screenings WHERE id = $id";
                if($result = $conn->query($sql)){
                    if($result->num_rows == 0){
                        header("Location: error.php?error=Ten%20seans%20już%20się%20odbył.");
                        exit();
                    }
                    while($row = $result->fetch_assoc()){
                        if($row['time']<strtotime(date('d-m-Y'))){
                            header("Location: error.php?error=Ten%20seans%20już%20się%20odbył.");
                            exit();
                        }
                    }
                }
                else{
                    header("Location: error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
                    exit();
                }

                $sql = "SELECT s.row_no, s.column_no FROM tickets t JOIN seats s ON t.seat_id = s.id WHERE t.screening_id = $id";
                if($result = $conn->query($sql)){
                    while($seat = $result->fetch_assoc()){
                        $row = $seat['row_no'];
                        $column = $seat['column_no'];
                        echo "seats[$column][$row] = 1;";
                    }
                }
                else{
                    header("Location: error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
                    exit();
                }
            }
        ?>
        </script>
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
        <?php if(isset($_GET['succ']) && $_GET['succ'] == 1)
            echo "<h1>Zarezerwowano miejsce</h1>";
        else if(!isset($_SESSION['user_id']))
            echo "Zaloguj się aby zarezerować miejsce";
        else { ?>
        <canvas></canvas>
        <form id="book-form" action="backend/book.php" method="POST">
            <h3>Rezerwujesz miejsce na seans:</h3>
            <?php
                $sql = "SELECT m.title, s.time, s.is_3D FROM screenings s JOIN movies m ON s.movie_id = m.id WHERE s.id = $id";
                if($result = $conn->query($sql)){
                    $row = $result->fetch_assoc();
                    $is_3D = $row['is_3D']?"3D":"";
                    echo "<h2>".$row['title']."<br>".date("d.m.Y H:i",$row['time'])." ".$is_3D."</h2>";
                }
                else{
                    header("Location: error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
                    exit();
                }
            ?>
            <input type="hidden" name="row"/>
            <input type="hidden" name="column"/>
            <input type="hidden" name="screening_id" value="<?php echo $_GET['id'] ?>"/>
            <input id="submit-btn" type="submit" value="Zarezerwuj" disabled>
        </form>
        <?php } ?>
        </div>
        <div id="footer">
            KINO by Iga Tupin 2021.
        </div>
    </body>
</html>