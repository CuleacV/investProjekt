<?php
global $connect;
session_start();
require_once '../DB/connect.php';

$login = $_POST['login'];
$password = $_POST['password'];

// Изменим запрос на выборку данных пользователя
$check_user = mysqli_query($connect, "SELECT * FROM investuser WHERE userName = '$login'");

if (mysqli_num_rows($check_user) > 0) {
    $user = mysqli_fetch_assoc($check_user);

    // Используем password_verify для проверки пароля
    if (password_verify($password, $user['pwhash'])) {
        $_SESSION['investUser'] = [
            "id" => $user['id'],
            "full_name" => $user['VorNachname'],
            "email" => $user['email']
        ];
        header('Location: ../controll/logika.php');
    } else {
        $_SESSION['message'] = 'Falscher Login oder Passwort';
        header('Location: ../index.php');
    }
} else {
    $_SESSION['message'] = 'Falscher Login oder Passwort';
    header('Location: ../index.php');
}
?>
