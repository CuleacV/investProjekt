<?php
session_start();

if (isset($_SESSION['investUser'])) {
    header('Location: controll/logika.php');
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

<!-- Autorisierungsformular -->

<form action="scripts/singIn.php" method="post">
    <label>Login</label>
    <input type="text" name="login" placeholder="Benutzernamen" required>
    <label>Password</label>
    <input type="password" name="password" placeholder="Dein Passwort" required>
    <button type="submit">SUBMIT</button>
    <p>
        Du hast noch keinen Account? - <a href='register.php'>Registrieren</a>!
    </p>
    <?php
    if (isset($_SESSION['message'])) {
        echo '<p class="msg">' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']);
    }
    ?>
</form>

</body>
</html>
