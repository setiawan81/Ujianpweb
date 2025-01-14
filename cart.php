<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

// Tambahkan item ke keranjang
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $quantity = $_POST['quantity'];

    $item_exists = false;

    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['item_id'] == $item_id) {
                $_SESSION['cart'][$index]['quantity'] += $quantity;
                $item_exists = true;
                break;
            }
        }

        if (!$item_exists) {
            $cart_item = array(
                'item_id' => $item_id,
                'item_name' => $item_name,
                'item_price' => $item_price,
                'quantity' => $quantity
            );
            $_SESSION['cart'][] = $cart_item;
        }
    } else {
        $_SESSION['cart'] = array(array(
            'item_id' => $item_id,
            'item_name' => $item_name,
            'item_price' => $item_price,
            'quantity' => $quantity
        ));
    }

    header("Location: cart.php");
    exit();
}

// Kurangi kuantitas item atau hapus item dari keranjang
if (isset($_POST['remove'])) {
    $item_id = $_POST['item_id'];

    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['item_id'] == $item_id) {
            if ($item['quantity'] > 1) {
                $_SESSION['cart'][$key]['quantity'] -= 1;
            } else {
                unset($_SESSION['cart'][$key]); // Hapus item jika kuantitas mencapai nol
            }
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
}

// Tambah kuantitas item
if (isset($_POST['add'])) {
    $item_id = $_POST['item_id'];

    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['item_id'] == $item_id) {
            $_SESSION['cart'][$key]['quantity'] += 1;
            break;
        }
    }
}

// Redirect ke halaman checkout setelah klik tombol Checkout
if (isset($_POST['checkout'])) {
    header("Location: checkout.php");
    exit();
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$total_price = 0;
foreach ($cart as $item) {
    $total_price += $item['item_price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('images/cart_bg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        h1 {
            font-family: 'Lobster', cursive;
        }
        .overlay {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .action-buttons button {
            margin: 0 2px;
        }
    </style>
</head>
<body>
    <div class="overlay text-center">
        <h1 class="text-4xl font-bold mb-8">Keranjang Belanja Anda</h1>

        <?php if (empty($cart)) { ?>
            <p class="text-center">Keranjang Anda kosong.</p>
        <?php } else { ?>
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="w-1/3 py-2 px-4">Nama Item</th>
                        <th class="w-1/4 py-2 px-4">Harga</th>
                        <th class="w-1/4 py-2 px-4">Kuantitas</th>
                        <th class="w-1/6 py-2 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $item) { ?>
                    <tr class="bg-gray-100">
                        <td class="border px-4 py-2"><?php echo $item['item_name']; ?></td>
                        <td class="border px-4 py-2"><?php echo number_format($item['item_price'], 0, ',', '.'); ?></td>
                        <td class="border px-4 py-2"><?php echo $item['quantity']; ?></td>
                        <td class="border px-4 py-2 action-buttons">
                            <form action="cart.php" method="POST" class="inline">
                                <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                <button type="submit" name="remove" class="bg-red-500 text-white px-2 py-1 rounded">Kurangi</button>
                                <button type="submit" name="add" class="bg-green-500 text-white px-2 py-1 rounded">Tambah</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p class="text-right font-bold mt-4">Total Harga: Rp <?php echo number_format($total_price, 0, ',', '.'); ?></p>
            <form action="cart.php" method="POST">
                <button type="submit" name="checkout" class="bg-blue-500 text-white px-4 py-2 rounded">Checkout</button>
            </form>
        <?php } ?>

        <div class="flex justify-center space-x-4 mt-8">
            <a href="view.php" class="bg-blue-500 text-white px-4 py-2 rounded">Lanjutkan Belanja</a>
        </div>
    </div>
</body>
</html>
