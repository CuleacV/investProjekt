<?php
require_once __DIR__ . '/../DB/connect.php';
require_once __DIR__ . '/../class/User.php';
session_start();
global $connect;

$full_name = $_POST['full_name'];
$login = $_POST['login'];
$email = $_POST['email'];
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];

// Проверка пароля на минимальную длину и наличие букв и цифр
if (strlen($password) < 6 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/\d/", $password)) {
    $_SESSION['message'] = 'Das Passwort muss länger als 6 sein und Symbole enthalten';
    header('Location: ../register.php');
    exit();
}

if ($password === $password_confirm) {
    try {
        $user = User::create($full_name, $login, $password, $email);
        $_SESSION['message'] = 'Registrierung erfolgreich !';
        header('Location: ../index.php');
    } catch (Exception $e) {
        $_SESSION['message'] = 'Fehler bei der Registrierung: ' . $e->getMessage();
        header('Location: ../register.php');
    }
} else {
    $_SESSION['message'] = 'Passwörter stimmen nicht überein !';
    header('Location: ../register.php');
}
?>

