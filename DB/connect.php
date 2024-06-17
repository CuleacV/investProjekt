<?php
$connect = mysqli_connect('localhost', 'root', '', 'investUser');

if (!$connect) {
    die('Fehler beim Herstellen der Verbindung zur Datenbank');
}
?>
