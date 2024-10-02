<?php
session_start();

include "./connect.php";
$stmt = $pdo->prepare("SELECT * FROM product WHERE pname LIKE ?");
$value = '%' . $_GET["q"] . '%';
$stmt->bindParam(1, $value);
$stmt->execute();

ob_start();
?>

<div class="flex gap-6 flex-wrap">
  <?php while ($row = $stmt->fetch()): ?>
    <div class="text-center flex flex-col">
      <a href="./product-detail.php?pid=<?= $row["pid"] ?>">
        <img src='../img/product/<?= $row["pid"] ?>' width='100'>
      </a>
      <p><?= $row["pname"] ?></p>
      <p><?= $row["price"] ?> บาท</p>
    </div>
  <?php endwhile; ?>
</div>

<?php
$content = ob_get_clean();

include "./layout.php";
?>