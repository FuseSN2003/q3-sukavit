<?php session_start();
if (empty($_SESSION["username"]) || $_SESSION["role"] != "admin") {
  header("location: ./main.php");
}
include "./connect.php";
$stmt = $pdo->prepare("SELECT * FROM product WHERE pname LIKE ?");
$value = '%' . $_GET["q"] . '%';
$stmt->bindParam(1, $value);
$stmt->execute();

ob_start();
?>

<script>
  function confirmDelete(pid) {
    var ans = confirm("ต้องการลบสินค้า " + pid);
    if (ans == true)
      document.location = `./delete-product.php?pid=${pid}`;
  }
</script>

<div class="flex flex-col gap-4 max-w-4xl w-full mx-auto">
  <div class="flex justify-between">
    <form>
      <input class="p-1 rounded-md border-1" type="text" name="q" placeholder="ค้าหาสินค้า">
      <button class="bg-gray-300 py-1 px-2 rounded-md border border-slate-500" type="submit">ค้าหา</button>
    </form>
    <a class="px-4 py-2 bg-primary text-white rounded-md" href="./add-product.php">เพิ่มสินค้า</a>
  </div>

  <table class="w-full border-collapse border border-slate-500 product-table">
    <thead>
      <tr>
        <th class="w-32">รหัสสินค้า</th>
        <th>ชื่อสินค้า</th>
        <th>ราคา</th>
        <th class="w-32">สินค้าในคลัง</th>
        <th>การจัดการ</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $stmt->fetch()): ?>
        <tr>
          <td><a href="./product-detail.php?pid=<?=$row["pid"]?>"><?= $row["pid"] ?></a></td>
          <td><a href="./product-detail.php?pid=<?=$row["pid"]?>"><?= $row["pname"] ?></a></td>
          <td><?= $row["price"] ?></td>
          <td><?= $row["quantity"] ?></td>
          <td>
            <a href="./edit-product.php?pid=<?= $row["pid"] ?>"><button class="mx-2 text-blue-500">แก้ไข</button></a>
            <button class="mx-2 text-red-500" onclick="confirmDelete('<?=$row['pid'] ?>')">ลบ</button>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php
$content = ob_get_clean();

include "./layout.php";
?>