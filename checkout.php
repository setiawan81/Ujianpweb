<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'db.php';
    $buyer_name = $_POST['buyer_name'];
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
    $total_price = 0;

    foreach ($cart as $item) {
        $total_price += $item['item_price'] * $item['quantity'];
    }

    // Simpan data pembelian
    $sql = "INSERT INTO orders (buyer_name, total_price) VALUES ('$buyer_name', '$total_price')";
    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id;

        // Simpan item dalam pembelian
        foreach ($cart as $item) {
            $item_name = $item['item_name'];
            $item_price = $item['item_price'];
            $quantity = $item['quantity'];
            $sql = "INSERT INTO order_items (order_id, item_name, item_price, quantity) VALUES ('$order_id', '$item_name', '$item_price', '$quantity')";
            $conn->query($sql);
        }

        // Kosongkan keranjang
        $_SESSION['cart'] = array();
    }

    $conn->close();

    // Redirect ke halaman receipt
    header("Location: receipt.php?buyer_name=" . urlencode($buyer_name) . "&order_id=" . $order_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('images/checkout_bg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .overlay {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="overlay text-center">
        <h1 class="text-4xl font-bold mb-8">Masukkan Nama Pembeli</h1>
        <form action="checkout.php" method="POST">
            <div class="mb-4">
                <label for="buyer_name" class="block text-left">Nama Pembeli:</label>
                <input type="text" id="buyer_name" name="buyer_name" required class="w-full px-4 py-2 rounded border">
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Checkout</button>
        </form>
    </div>
</body>
</html>
