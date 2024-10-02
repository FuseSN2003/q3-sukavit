<?php
session_start();

$upload_dir = "../img/member/";
include "./connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["name"]) && isset($_POST["address"]) && isset($_POST["mobile"]) && isset($_POST["email"])) {    
    $stmt = $pdo->prepare("INSERT INTO member (username, password, name, address, mobile, email) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bindParam(1, $_POST["username"]);
    $stmt->bindParam(2, $_POST["password"]);
    $stmt->bindParam(3, $_POST["name"]);
    $stmt->bindParam(4, $_POST["address"]);
    $stmt->bindParam(5, $_POST["mobile"]);
    $stmt->bindParam(6, $_POST["email"]);
    
    if ($stmt->execute()) {
      if (isset($_FILES["image"])) {
        $image_file_type = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $path = $upload_dir . basename($_POST["username"] . "." . $image_file_type);

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $path)) {
          $message = "สมัครสมาชิกสำเร็จ";
          $error = false;
        } else {
          $message = "อัพโหลดไฟล์ไม่สำเร็จ";
          $error = true;
        }
      }
    } else {
      $message = "สมัครสมาชิกไม่สำเร็จ";
      $error = true;
    }
  }
}

ob_start();
?>


<h1 class="text-4xl font-bold mb-4">สมัครสมาชิก</h1>
<div class="flex gap-8">
  <form class="add-product-form" action="./register.php" method="post" enctype="multipart/form-data">
    <div>
      <label for="username">ชื่อผู้ใช้</label>
      <input required class="w-[300px]" type="text" name="username">
    </div>
    <div>
      <label for="password">รหัสผ่าน</label>
      <input required class="w-[300px]" type="password" name="password">
    </div>
    <div>
      <label for="name">ชื่อ-สกุล</label>
      <input required class="w-[300px]" type="type" name="name">
    </div>
    <div>
      <label for="address">ที่อยู่</label>
      <textarea cols="4" class="w-[300px] border" type="text" name="address"></textarea>
    </div>
    <div>
      <label for="mobile">เบอร์โทรศัพท์</label>
      <input required class="w-[300px]" pattern="[0-9]+" name="mobile">
    </div>
    <div>
      <label for="email">อีเมล</label>
      <input required class="w-[300px]" type="email" name="email">
    </div>
    <div>
      <input required class="w-[300px]" accept="image/*" type="file" name="image" id="image">
    </div>
    <button type="submit" class="border bg-primary text-white rounded-md py-1">เพิ่มสมาชิก</button>
    <?php if (isset($error) && $error): ?>
      <p class="text-red-500"><?= $message ?></pc>
      <?php elseif (isset($error) && !$error): ?>
        <p class="text-green-500"><?=$message?></p>
        <a href="./login.php">เข้าสู่ระบบ</a>
      <?php endif ?>
  </form>

  <div id="image-preview">
  </div>
</div>

<?php
$content = ob_get_clean();

include "./layout.php";
?>