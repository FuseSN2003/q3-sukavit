<?php
session_start();
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./style.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="./script.js"></script>
  <style type="text/tailwindcss">
    @layer base {
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }
      
      input, textarea{
        @apply border p-1;
      }
    }
  </style>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#408040',
            secondary: '#efffef'
          }
        }
      }
    }
  </script>
</head>

<body>
  <div class="flex min-h-screen">
    <aside>
      <div class="w-[250px] bg-secondary h-full sticky top-0">
        <div class="sticky top-0">
          <a href="./main.php"><img src="./assets/cslogo.jpg" /></a>
          <div class="py-4 flex flex-col items-center">
            <h3 class="text-2xl font-bold text-[#008000]">Navigation</h3>
            <ul class="w-full p-8 navigation-menu">
              <a href="./main.php">
                <li>Home</li>
              </a>
              <a href="./product-list.php">
                <li>Product list</li>
              </a>
              <a href="./member-list.php">
                <li>Member list</li>
              </a>
              <?php if (!empty($_SESSION["username"])): ?>
                <li><a href="logout.php">ออกจากระบบ</a></li>
              <?php else: ?>
                <li><a href="login.php">เข้าสู่ระบบ</a></li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
    </aside>
    <div class="grow">
      <header class="h-24 bg-[#80a080] sticky top-0 flex items-center justify-end px-8">
        <form action="./main.php" class="flex gap-2">
          <input name="q" class="p-1 rounded-md border-0" placeholder="Search the site..." />
          <button class="bg-gray-300 p-1.5 rounded-md border border-black">Search</button>
        </form>
      </header>
      <main class="p-8"><?= $content; ?></main>
    </div>
  </div>
</body>