<?php
session_start();
header('Content-Type: application/json');

if (!empty($_SESSION['admin_logged_in'])) {
    echo json_encode(["logged_in" => true]);
} else {
    echo json_encode(["logged_in" => false]);
}
?>
