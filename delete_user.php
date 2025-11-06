<?php
require 'db.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM usersagrofood WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

header("Location: admin_users.php");
exit;
?>
