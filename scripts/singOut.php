<?php
require_once __DIR__ . '/../DB/connect.php';
session_start();
global $connect;

$full_name = $_POST['full_name'];
$login = $_POST['login'];
$email = $_POST['email'];
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];

if ($password === $password_confirm) {
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO investuser (VorNachname, userName, email, pwhash) VALUES ('$full_name', '$login', '$email', '$password_hashed')";
    mysqli_query($connect, $query);

    $_SESSION['message'] = 'Registrierung erfolgreich !';
    header('Location: ../index.php');
} else {
    $_SESSION['message'] = 'Passwörter stimmen nicht überein !';
    header('Location: ../register.php');
}
?>
