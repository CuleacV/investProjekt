<?php
require_once __DIR__ . '/../DB/connect.php';
require_once __DIR__ . '/../class/User.php';
session_start();
global $connect;

$login = $_POST['login'];
$password = $_POST['password'];

$check_user = mysqli_query($connect, "SELECT * FROM investuser WHERE userName = '$login'");
if (mysqli_num_rows($check_user) > 0) {
    $user_data = mysqli_fetch_assoc($check_user);
    if (password_verify($password, $user_data['pwhash'])) {
        $user = new User($user_data['id'], $user_data['VorNachname'], $user_data['userName'], $user_data['email'], $user_data['pwhash']);
        $_SESSION['investUser'] = [
            "id" => $user->getId(),
            "full_name" => $user->getVorNachname(),
            "email" => $user->getEmail()
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

