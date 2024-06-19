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
    <title>Add User</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

<!-- Registrierungsformular -->

<form action="scripts/singOut.php" method="post">
    <label>Vorname und Nachname</label>
    <input type="text" name="full_name" placeholder="Vorname und Nachname" required>
    <label>Login</label>
    <input type="text" name="login" placeholder="User Name" required>
    <label>Email</label>
    <input type="text" name="email" placeholder="Dein Email" required>
    <label>Password</label>
    <input type="password" name="password" placeholder="Gib deinen Passwort ein" required>
    <label>Repeat Password</label>
    <input type="password" name="password_confirm" placeholder="Wiederhole dein Passwort" required>
    <button type="submit">SUBMIT</button>
    <p>
        Sie haben ein Account - <a href='index.php'>Autorisierung</a>!
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
