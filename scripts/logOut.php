<?php
session_start();
unset($_SESSION['investUser']);
header('Location: ../index.php');
?>
