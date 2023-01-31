<?php
require '../config/config.php';
$stmt = $pdo->prepare("DELETE from posts WHERE id=" . $_GET['id']);
$stmt->execute();


echo "<script>alert('Deleted Successfully');window.location.href='index.php';</script>";
