<?php
include "./connect.php";

$stmt = $pdo->prepare("SELECT * FROM member WHERE username = ?");
$stmt->bindParam(1, $_GET["username"]);
$stmt->execute();
$row = $stmt->fetch();

ob_start();
?>

<?php if (!empty($row)): ?>
  <div class="flex gap-6">
    <img src="../img/member/<?= $row["username"] ?>" alt="<?= $row["name"] ?>" width="300px">
    <div>
      <p>ชื่อผู้ใช้: <?= $row["username"] ?></p>
      <p>ชื่อ-สกุล: <?= $row["name"] ?></p>
      <p>ที่อยู่: <?= $row["address"] ?></p>
      <p>เบอร์โทรศัพท์: <?= $row["mobile"] ?></p>
      <p>อีเมล: <?= $row["email"] ?></p>
      <p>role: <?= $row["role"] ?></p>
    </div>
  </div>
<?php else: ?>
  <div class="text-center flex flex-col gap-6">
    <h1 class="text-3xl font-bold">ไม่พบผู้ใช้งาน</h1>
    <a class="underline text-primary" href="./main.php">กลับไปยังหน้าแรก</a>
  </div>
<?php endif; ?>


<?php
$content = ob_get_clean();

include "./layout.php";
?>