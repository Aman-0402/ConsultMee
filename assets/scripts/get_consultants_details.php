<?php
session_start();

if (isset($_SESSION['consultant'])) {
    echo json_encode($_SESSION['consultant']);
} else {
    echo json_encode(['error' => 'Consultant not logged in']);
}
?>