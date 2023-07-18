<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="utf-8"/>
        <title>Kino - Harmonogram</title>
        <link rel="stylesheet" href="styles/style.css"/>
        <link rel="stylesheet" href="styles/harmonogram.css"/>
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
        <table id="schedule-table">
            <?php
                if(isset($_GET['week']))
                    $week = $_GET['week'];
                else
                    $week = 0;
                $this_week_monday = strtotime(date('d-m-Y')) - (date('w')+6)%7*24*60*60 + $week*7*24*60*60;
                $this_week_sunday = $this_week_monday + 7*24*60*60;
                $day_names = array(
                    0 => 'Pon',
                    1 => 'Wt',
                    2 => 'Śr',
                    3 => 'Czw',
                    4 => 'Pt',
                    5 => 'Sob',
                    6 => 'Nd'  
                ); 
                echo "<tr><td></td>";
                for($i = 0;$i<7;$i++){
                    $tmp_day = $this_week_monday + $i*24*60*60;
                    echo "<th>".$day_names[$i]."<br>".date('d-m-Y',$tmp_day)."</th>";
                }
                echo "</tr>";

                require_once "backend/connect.php";
                $conn = @new mysqli($host, $db_user, $db_password, $db_name);
                if($conn->connect_errno!=0){
                    header("Location: error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
                    exit();
                }

                if(isset($_GET['movie'])){
                    $movieID = $_GET['movie'];
                    $sql = "SELECT s.id, s.time, s.is_3D, m.title FROM screenings s JOIN movies m ON s.movie_id = m.id WHERE s.time >= $this_week_monday AND s.time <= $this_week_sunday AND m.id = $movieID ORDER BY s.time ASC";
                }
                else
                    $sql = "SELECT s.id, s.time, s.is_3D, m.title FROM screenings s JOIN movies m ON s.movie_id = m.id WHERE s.time >= $this_week_monday AND s.time <= $this_week_sunday ORDER BY s.time ASC";
                if($result = $conn->query($sql)){
                    $rows = array(
                        10 => "<tr><td>10:00</td>",
                        11 => "<tr><td>11:00</td>",
                        12 => "<tr><td>12:00</td>",
                        13 => "<tr><td>13:00</td>",
                        14 => "<tr><td>14:00</td>",
                        15 => "<tr><td>15:00</td>",
                        16 => "<tr><td>16:00</td>",
                        17 => "<tr><td>17:00</td>",
                        18 => "<tr><td>18:00</td>",
                        19 => "<tr><td>19:00</td>",
                        20 => "<tr><td>20:00</td>",
                        21 => "<tr><td>21:00</td>"
                    );
                    $screening = $result->fetch_assoc();
                    for($d = 0;$d<7;$d++){
                        for($h = 10;$h<22;$h++){
                            if($screening != null && $screening['time']<$this_week_monday+$d*24*60*60+($h+1)*60*60){
                                $is3D = "";
                                if($screening['is_3D'])
                                    $is3D = "3D";
                                $rows[$h] = $rows[$h]."<td>".($screening['time']>strtotime(date('d-m-Y H:i'))?("<a href='booking.php?id=".$screening['id']."'>".$screening['title']."</a>"):$screening['title'])."<br>".date('H:i', $screening['time'])." ".$is3D."</td>";
                                $screening = $result->fetch_assoc();
                            }
                            else
                                $rows[$h] = $rows[$h]."<td>-<br>-</td>";   
                        }
                    }
                    for($i = 10;$i<22;$i++){
                        echo $rows[$i]."</tr>";
                    }
                }
                else{
                    header("Location: error.php?error=Wystąpił%20błąd.%20Spróbuj%20ponownie%20później.");
                    exit();
                }
            ?>
        </table>
        <div id="week-buttons">
            <form action="harmonogram.php" method="GET">
                <?php if($week > 0) { ?><button name="week" value="<?php echo $week-1 ?>"><</button><?php } ?>
                <button name="week" value="<?php echo $week+1 ?>">></button>
            <?php if(isset($movieID)) { ?><input type="hidden" name="movie" value="<?php echo $movieID ?>"/><?php } ?>
            </form>
        </div>
        </div>
        <div id="footer">
            KINO by Iga Tupin 2021.
        </div>
    </body>
</html>