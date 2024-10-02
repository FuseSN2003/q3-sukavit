<?php session_start();
if (empty($_SESSION["username"]) || $_SESSION["role"] != "admin") {
  header("location: ./main.php");
}
include "./connect.php";

$stmt = $pdo->prepare("SELECT product.pname, orders.ord_date, orders.ord_id, member.username, member.name, item.quantity, member.role
FROM member
LEFT JOIN orders ON member.username = orders.username
LEFT JOIN item ON orders.ord_id = item.ord_id
LEFT JOIN product ON item.pid = product.pid
WHERE member.username LIKE ?
      OR member.name LIKE ?
");
$value = '%' . $_GET["q"] . '%';
$stmt->bindParam(1, $value);
$stmt->bindParam(2, $value);
$stmt->execute();

$data = [];

while ($row = $stmt->fetch()) {
  if (!isset($data[$row["username"]])) {
    $data[$row["username"]] = [
      "name" => $row["name"],
      "role" => $row["role"],
      "orders" => []
    ];
  }

  if ($row["ord_id"] !== null) {
    if (!isset($data[$row["username"]]["orders"][$row["ord_id"]])) {
      $data[$row["username"]]["orders"][$row["ord_id"]] = [
        "order_date" => $row["ord_date"],
        "products" => []
      ];
    }

    if ($row["pname"] !== null) {
      $data[$row["username"]]["orders"][$row["ord_id"]]["products"][] = [
        "pname" => $row["pname"],
        "quantity" => $row["quantity"]
      ];
    }
  }
}

ob_start();
?>

<head>
  <style>
    .order-details {
      display: none;
    }
  </style>
  <script>
    function confirmDelete(username) {
      var ans = confirm("ต้องการลบสมาชิก " + username);
      if (ans == true)
        document.location = `./delete-member.php?username=${username}`;
    }
  </script>
</head>

<div class="flex flex-col gap-4 max-w-4xl w-full mx-auto">
  <div class="flex justify-between">
    <form>
      <input class="p-1 rounded-md border-1" type="text" name="q" placeholder="ค้นหาสมาชิก">
      <button class="bg-gray-300 py-1 px-2 rounded-md border border-slate-500" type="submit">ค้นหา</button>
    </form>
    <a class="px-4 py-2 bg-primary text-white rounded-md" href="./add-member.php">เพิ่มสมาชิก</a>
  </div>

  <table class="w-full border-collapse border border-slate-500 product-table">
    <thead>
      <tr>
        <th>avatar</th>
        <th>Username</th>
        <th>ชื่อ-สกุล</th>
        <th>จำนวนออร์เดอร์</th>
        <th>การจัดการ</th>
        <th>Role</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data as $username => $memberData): ?>
        <tr>
          <td><a href="./member-detail.php?username=<?=$username?>"><img class="mx-auto" src="../img/member/<?= $username ?>" alt="<?= $username ?>" width="50"></a></td>
          <td><a href="./member-detail.php?username=<?=$username?>"><?= $username ?></a></td>
          <td><?= $memberData["name"] ?></td>
          <td class="orders" data-state="close"><?= count($memberData["orders"]) ?> รายการ <button
              class="font-semibold underline">ดูทั้งหมด</button>
            <div class="order-detail">
              <?php foreach ($memberData["orders"] as $orderId => $orderData): ?>
                <div class="border-b border-black p-1">
                  <p><strong>Order id:<?= $orderId ?></strong></p>
                  <ul>
                    <?php foreach ($orderData["products"] as $product): ?>
                      <li class="flex items-between justify-between"><span><?= $product["pname"] ?></span>
                        <span>x<?= $product["quantity"] ?></span>
                      </li>
                    <?php endforeach; ?>
                    </uld>
                </div>
              <?php endforeach ?>
            </div>
          </td>
          <td><a href="./edit-member.php?username=<?= $username ?>"><button class="mx-2 text-blue-500">แก้ไข</button></a>
            <button class="mx-2 text-red-500" onclick="confirmDelete('<?= $username ?>')">ลบ</button>
          </td>
          <td><?=$memberData["role"]?>
        </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php
$content = ob_get_clean();

include "./layout.php";
?>