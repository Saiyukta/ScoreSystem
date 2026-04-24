<?php 
include 'config.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $conn->query("UPDATE warriors SET approved = 1 WHERE id = $id");
}
header("Location: admin.php");
exit; 
?>