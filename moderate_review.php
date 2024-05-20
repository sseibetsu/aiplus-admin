<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'aiplus');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$id = (int)$_POST['id'];

if (isset($_POST['approve'])) {
    $mysqli->query("UPDATE reviews SET status='approved' WHERE id=$id");
} elseif (isset($_POST['reject'])) {
    $mysqli->query("DELETE FROM reviews WHERE id=$id");
} elseif (isset($_POST['delete'])) {
    $mysqli->query("DELETE FROM reviews WHERE id=$id");
}

header('Location: admin.php');
exit;
?>
