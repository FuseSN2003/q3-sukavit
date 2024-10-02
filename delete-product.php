<?php
session_start();

if (empty($_SESSION["username"]) || $_SESSION["role"] != "admin") {
  header("location: ./main.php");
}

include "./connect.php";

$stmt = $pdo->prepare("DELETE FROM product WHERE pid=?");
$stmt->bindParam(1, $_GET['pid']);
if ($stmt->execute()) {
  header("location: ./product-list.php");
}