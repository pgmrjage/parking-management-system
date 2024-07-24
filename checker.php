<?php
session_start();
include('config.php');
if (isset($_SESSION["username"])) {
    if ($_SESSION['type'] == 0) {
        header("Location: index.php");
        exit();
    }
    if ($_SESSION['type'] == 1) {
        header("Location: admin.php");
        exit();
    }
}

?>