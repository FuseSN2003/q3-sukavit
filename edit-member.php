<?php
session_start();

if (empty($_SESSION["username"]) || $_SESSION["role"] != "admin") {
  header("location: ./main.php");
}

$upload_dir = "../img/member/";
include "./connect.php";

if (isset($_GET["username"])) {
  $stmt = $pdo->prepare("SELECT * FROM member WHERE username = ?");
  $stmt->bindParam(1, $_GET["username"]);
  $stmt->execute();
  $row = $stmt->fetch();
}

$message = null;
if (isset($_GET["message"])) {
  if ($_GET["message"] == "success") {
    $message = "แก้ไขสมาชิกสำเร็จ";
  } elseif ($_GET["message"] == "error") {
    $message = "แก้ไขสมาชิกไม่สำเร็จ";
  } else if ($_GET["message"] == "upload-error") {
    $message = "อัพโหลดไฟล์ไม่สำเร็จ";
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $error;
  if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["name"]) && isset($_POST["address"]) && isset($_POST["mobile"]) && isset($_POST["email"])) {
    $stmt = $pdo->prepare("UPDATE member SET password=?, name=?, address=?, mobile=?, email=?, role=? WHERE username=?");
    $stmt->bindParam(1, $_POST["password"]);
    $stmt->bindParam(2, $_POST["name"]);
    $stmt->bindParam(3, $_POST["address"]);
    $stmt->bindParam(4, $_POST["mobile"]);
    $stmt->bindParam(5, $_POST["email"]);
    $stmt->bindParam(6, $_POST["role"]);
    $stmt->bindParam(7, $_POST["username"]);

    if ($stmt->execute()) {
      $username = $_POST["username"];

      if (isset($_FILES["image"]) && $_FILES["image"]["error"] != UPLOAD_ERR_NO_FILE) {
        $image_file_type = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $path = $upload_dir . basename($username . "." . $image_file_type);

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
    header("Location: ./edit-member.php?username=" . $_POST["username"] . "&message=" . $error);
  }
}

ob_start();
?>

<?php if (!empty($row)): ?>
  <h1 class="text-4xl font-bold mb-4">แก้ไขสมาชิก</h1>
  <div class="flex gap-8">
    <form class="add-product-form" action="./edit-member.php?username=<?= $row["username"] ?>" method="post"
      enctype="multipart/form-data">
      <input hidden value="<?= $row["username"] ?>" name="username" />
      <div>
        <label for="password">รหัสผ่าน</label>
        <input value="<?= $row["password"] ?>" required class="w-[300px]" type="password" name="password">
      </div>
      <div>
        <label for="name">ชื่อ-สกุล</label>
        <input value="<?= $row["name"] ?>" required class="w-[300px]" type="type" name="name">
      </div>
      <div>
        <label for="address">ที่อยู่</label>
        <textarea cols="4" class="w-[300px] border" type="text" name="address"><?= $row["address"] ?></textarea>
      </div>
      <div>
        <label for="mobile">เบอร์โทรศัพท์</label>
        <input value="<?= $row["mobile"] ?>" required class="w-[300px]" name="mobile">
      </div>
      <div>
        <label for="email">อีเมล</label>
        <input value="<?= $row["email"] ?>" required class="w-[300px]" type="email" name="email">
      </div>
      <div>
        <input class="w-[300px]" accept="image/*" type="file" name="image" id="image">
      </div>
      <div>
        <label for="role"></label>
        <select name="role" id="role">
          <?php if ($row["role"] == "member"): ?>
            <option selected value="member">member</option>
            <option value="admin">admin</option>
          <?php elseif ($row["role" == "admin"]): ?>
            <option value="member">member</option>
            <option selected value="admin">admin</option>
          <?php endif; ?>
        </select>
      </div>
      <button type="submit" class="border bg-primary text-white rounded-md py-1">แก้ไขสมาชิก</button>
      <?php if ($message): ?>
        <p class="<?= ($_GET['message'] == 'success') ? 'text-green-500' : 'text-red-500' ?>"><?= $message ?></p>
      <?php endif; ?>
    </form>

    <div id="image-preview">
      <img width="250px" src="../img/member/<?= $row["username"] ?>" alt="preview">
    </div>
  </div>
<?php else: ?>
<?php endif; ?>

<?php
$content = ob_get_clean();

include "./layout.php";
?>