<?php
session_start();

if (empty($_SESSION["username"]) || $_SESSION["role"] != "admin") {
  header("location: ./main.php");
}

include "./connect.php";

$stmt = $pdo->prepare("DELETE FROM member WHERE username=?");
$stmt->bindParam(1, $_GET['username']);

if ($stmt->execute()) {
  header("location: ./member-list.php");
}