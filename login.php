<?php
session_start();

include "./connect.php";

if($_SESSION["username"]) {
  header("Location: ./main.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["username"]) && isset($_POST["password"])) {
    $stmt = $pdo->prepare("SELECT * FROM member WHERE username = ? AND password = ?");
    $stmt->bindParam(1, $_POST["username"]);
    $stmt->bindParam(2, $_POST["password"]);
    $stmt->execute();
    $row = $stmt->fetch();

    if (!empty($row)) {
      $_SESSION["username"] = $row["username"];
      $_SESSION["role"] = $row["role"];
      header("Location: ./main.php");
    } else {
      $loginMessage = "เข้าสู่ระบบไม่สำเร็จ";
      $error = true;
    }
  }
}

ob_start();
?>

<form action="./login.php" method="POST">
  <div class="flex flex-col">
    <label for="username">Username:</label>
    <input class="w-[200px] px-2 py-1" type="text" name="username">
  </div>
  <div class="flex flex-col">
    <label for="username">Password:</label>
    <input class="w-[200px] px-2 py-1" type="password" name="password">
  </div>
  <button type="submit" class="mt-2 py-2 px-4 bg-primary text-white rounded-md">Login</button>
</form>

<a href="./register.php" class="hover:underline">สมัครสมาชิก</a>

<p class="<?= $error ? "text-red-500" : "text-green-500" ?>"><?= $loginMessage ?></p>
<?php if(isset($error) && !$error): ?> 
  <a href="./main.php" class="hover:underline">ไปหน้าแรก</a>
<?php endif ?>

<?php
$content = ob_get_clean();

include "./layout.php";
?>