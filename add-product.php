<?php
session_start();

if (empty($_SESSION["username"]) || $_SESSION["role"] != "admin") {
  header("location: ./main.php");
}

$upload_dir = "../img/product/";
include "./connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["pname"]) && isset($_POST["pdetail"]) && isset($_POST["price"]) && isset($_POST["quantity"])) {
    $stmt = $pdo->prepare("INSERT INTO product (pname, pdetail, price, quantity) VALUES (?, ?, ?, ?)");
    $stmt->bindParam(1, $_POST["pname"]);
    $stmt->bindParam(2, $_POST["pdetail"]);
    $stmt->bindParam(3, $_POST["price"]);
    $stmt->bindParam(4, $_POST["quantity"]);

    if ($stmt->execute()) {
      $pid = $pdo->lastInsertId();

      if (isset($_FILES["image"])) {
        $image_file_type = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $path = $upload_dir . basename($pid . "." . $image_file_type);

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $path)) {
          $message = "เพิ่มสินค้าสำเร็จ";
          $error = false;
        } else {
          $message = "อัพโหลดไฟล์ไม่สำเร็จ";
          $error = true;
        }
      }
    } else {
      $message = "เพิ่มสินค้าไม่สำเร็จ";
      $error = true;
    }
  }
}

ob_start();
?>

<h1 class="text-4xl font-bold mb-4">เพิ่มสินค้า</h1>
<div class="flex gap-8">
  <form class="add-product-form" action="./add-product.php" method="post" enctype="multipart/form-data">
    <div>
      <label for="pname">ชื่อสินค้า</label>
      <input required class="w-[300px]" type="text" name="pname">
    </div>
    <div>
      <label for="pdetail">รายละเอียดสินค้า</label>
      <textarea cols="4" class="w-[300px] border" type="text" name="pdetail"></textarea>
    </div>
    <div>
      <label for="price">ราคา</label>
      <input required class="w-[300px]" type="number" name="price">
    </div>
    <div>
      <label for="quantity">จำนวน</label>
      <input required class="w-[300px]" type="number" name="quantity">
    </div>
    <div>
      <input required class="w-[300px]" accept="image/*" type="file" name="image" id="image">
    </div>
    <button type="submit" class="border bg-primary text-white rounded-md py-1">เพิ่มสินค้า</button>
    <?php if (isset($error) && $error): ?>
      <p class="text-red-500"><?= $message ?></pc>
      <?php elseif (isset($error) && !$error): ?>
        <a href="./product-detail.php?pid=<?= $pid ?>">ดูรายละเอียดสินค้า</a>
      <?php endif ?>
  </form>

  <div id="image-preview">
  </div>
</div>

<?php
$content = ob_get_clean();

include "./layout.php";
?>