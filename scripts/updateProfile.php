<?php
session_start();
require_once '../class/User.php';

if (!isset($_SESSION['investUser'])) {
    header('Location: ../index.php');
    exit();
}

$user = User::findById($_SESSION['investUser']['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];
    $newPassword = $_POST['password'];

    if (!empty($newUsername)) {
        $user->setUserName($newUsername);
    }
    if (!empty($newEmail)) {
        $user->setEmail($newEmail);
    }
    if (!empty($newPassword)) {
        $user->setPwhash(password_hash($newPassword, PASSWORD_DEFAULT));
    }

    // Save changes to database
    $con = User::dbcon();
    $sql = 'UPDATE investuser SET userName = :userName, email = :email, pwhash = :pwhash WHERE id = :id';
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':userName', $user->getUserName());
    $stmt->bindParam(':email', $user->getEmail());
    $stmt->bindParam(':pwhash', $user->getPwhash());
    $stmt->bindParam(':id', $user->getId());
    $stmt->execute();

    $_SESSION['message'] = 'Profile updated successfully';
    header('Location: ../controll/logika.php');
}
?>
