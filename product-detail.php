<?php
include "./connect.php";

$stmt = $pdo->prepare("SELECT * FROM product WHERE pid = ?");
$stmt->bindParam(1, $_GET["pid"]);
$stmt->execute();
$row = $stmt->fetch();

ob_start();
?>


<?php if (!empty($row)): ?>
  <div class="flex gap-6">
    <img src="../img/product/<?= $row["pid"] ?>" alt="<?= $row["pname"] ?>" width="300px">
    <div>
      <p>ชื่อสินค้า: <?= $row["pname"] ?></p>
      <p>รายละเอียดสินค้า: <?= $row["pdetail"] ?></p>
      <p>ราคา: <?= $row["price"] ?> บาท</p>
    </div>
  </div>
<?php else: ?>
  <div class="text-center flex flex-col gap-6">
    <h1 class="text-3xl font-bold">ไม่พบสินค้า</h1>
    <a class="underline text-primary" href="./main.php">กลับไปยังหน้าแรก</a>
  </div>
<?php endif; ?>


<?php
$content = ob_get_clean();

include "./layout.php";
?>