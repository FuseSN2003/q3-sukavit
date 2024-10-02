<?php
session_start();

if (empty($_SESSION["username"]) || $_SESSION["role"] != "admin") {
  header("location: ./main.php");
}

$upload_dir = "../img/product/";
include "./connect.php";

if (isset($_GET["pid"])) {
  $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = ?");
  $stmt->bindParam(1, $_GET["pid"]);
  $stmt->execute();
  $row = $stmt->fetch();
}

$message = null;
if (isset($_GET["message"])) {
  if ($_GET["message"] == "success") {
    $message = "แก้ไขสินค้าสำเร็จ";
  } elseif ($_GET["message"] == "error") {
    $message = "แก้ไขสินค้าไม่สำเร็จ";
  } else if ($_GET["message"] == "upload-error") {
    $message = "อัพโหลดไฟล์ไม่สำเร็จ";
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $error;
  if (isset($_POST["pname"]) && isset($_POST["pdetail"]) && isset($_POST["price"]) && isset($_POST["quantity"])) {
    $stmt = $pdo->prepare("UPDATE product SET pname=?, pdetail=?, price=?, quantity=? WHERE pid=?");
    $stmt->bindParam(1, $_POST["pname"]);
    $stmt->bindParam(2, $_POST["pdetail"]);
    $stmt->bindParam(3, $_POST["price"]);
    $stmt->bindParam(4, $_POST["quantity"]);
    $stmt->bindParam(5, $_POST["pid"]);

    if ($stmt->execute()) {
      $pid = $_POST["pid"];

      if (isset($_FILES["image"]) && $_FILES["image"]["error"] != UPLOAD_ERR_NO_FILE) {
        $image_file_type = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $path = $upload_dir . basename($pid . "." . $image_file_type);

        if (file_exists($path)) {
          unlink($path);
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $path)) {
          $error = "success";
        } else {
          $error = "upload-error";
        }
      }

      $error = "success";
    } else {
      $error = "error";
    }
    header("Location: ./edit-product.php?pid=" . $_POST["pid"] . "&message=" . $error);
  }
}

ob_start();
?>

<?php if (!empty($row)): ?>
  <h1 class="text-4xl font-bold mb-4">แก้ไขสินค้า</h1>
  <div class="flex gap-8">
    <form class="add-product-form" action="./edit-product.php?pid=<?= $row["pid"] ?>" method="post"
      enctype="multipart/form-data">
      <input hidden value="<?= $row["pid"] ?>" name="pid" />
      <div>
        <label for="pname">ชื่อสินค้า</label>
        <input value="<?= $row["pname"] ?>" required class="w-[300px]" type="text" name="pname">
      </div>
      <div>
        <label for="pdetail">รายละเอียดสินค้า</label>
        <textarea cols="4" class="w-[300px] border" type="text" name="pdetail"><?= $row["pdetail"] ?></textarea>
      </div>
      <div>
        <label for="price">ราคา</label>
        <input value="<?= $row["price"] ?>" required class="w-[300px]" type="number" name="price">
      </div>
      <div>
        <label for="quantity">จำนวน</label>
        <input value="<?= $row["quantity"] ?>" required class="w-[300px]" type="number" name="quantity">
      </div>
      <div>
        <input class="w-[300px]" accept="image/*" type="file" name="image" id="image">
      </div>
      <button type="submit" class="border bg-primary text-white rounded-md py-1">แก้ไขสินค้า</button>
      <?php if ($message): ?>
        <p class="<?= ($_GET['message'] == 'success') ? 'text-green-500' : 'text-red-500' ?>"><?= $message ?></p>
      <?php endif; ?>
    </form>

    <div id="image-preview">
      <img width="250px" src="../img/product/<?= $row["pid"] ?>" alt="preview">
    </div>
  </div>
<?php else: ?>
<?php endif; ?>

<?php
$content = ob_get_clean();

include "./layout.php";
?>