<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="utf-8"/>
        <title>Kino - Rejestracja</title>
        <link rel="stylesheet" href="styles/style.css"/>
        <link rel="stylesheet" href="styles/register.css"/>
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
            <form id="register-form" action="backend/registration.php" method="POST">
                <h1>REJESTRACJA</h1>
                <label for="login-input">Login:</label>
                <input class="register-input" id="login-input" type="text" name="login" />
                <label for="password1-input">Hasło:</label>
                <input class="register-input" id="password1-input" type="password" name="password1" />
                <label for="password2-input">Powtórz hasło:</label>
                <input class="register-input" id="password2-input" type="password" name="password2" />
                <span>
                    <input id="checkbox" type="checkbox" name="agreement" />
                    Akceptuję regulamin kina i wyrażam zgode na przetwarzanie moich danych osobowych
                </span>
                <input type="submit" value="Stwórz konto"/>
            </form>
            <?php
                $errorsMsgs['different-passwords'] = 'Podane hasła nie są takie same.';
                $errorsMsgs['login-taken'] = 'Podany login jest zajęty.';
                $errorsMsgs['login-empty'] = 'Nie podano loginu.';
                $errorsMsgs['password-empty'] = 'Nie podano hasła.';
                $errorsMsgs['login-short'] = 'Login powinien mieć od 4 do 20 znaków.';
                $errorsMsgs['password-short'] = 'Hasło powinno mieć od 6 do 20 znaków.';
                $errorsMsgs['agreement-empty'] = 'Musisz zaakceptować regulamin.';
                $errorsMsgs['no-account'] = 'Niepoprawny login lub hasło.<br>Nie posiadasz konta? Zarejestruj się!';

                if(!empty($_GET['error'])){
                    $errors = explode(',',$_GET['error']);
                    echo "<p style='color:red' id='return-msg'>";
                    for($i = 0;$i < count($errors);$i++)
                        echo $errorsMsgs[$errors[$i]].'<br>';
                    echo "</p>";
                }

                if(!empty($_GET['succ'])){
                    echo "<p style='color:green' id='return-msg'>Zarejestrowano pomyślnie. :)</p>";
                }
            ?>
        </div>
        <div id="footer">
            KINO by Iga Tupin 2021.
        </div>
    </body>
</html>