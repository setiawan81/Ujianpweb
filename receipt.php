<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$buyer_name = isset($_GET['buyer_name']) ? $_GET['buyer_name'] : '';
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Belanja</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('images/checkout_bg.jpg');
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
        .receipt {
            background: rgba(255, 255, 255, 1.0);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="overlay text-center">
        <h1 class="text-4xl font-bold mb-8">Struk Belanja</h1>
        <p class="text-xl mb-4">Terima kasih, <?php echo htmlspecialchars($buyer_name); ?>!</p>

        <div class="receipt text-left">
            <p class="text-xl font-bold mb-4">Struk Belanja</p>
            <p class="mb-2">Nama Pembeli: <?php echo htmlspecialchars($buyer_name); ?></p>
            <p class="mb-2">Tanggal Pembelian: <?php echo date("d-m-Y H:i"); ?></p>
            <hr class="mb-4">
            
            <ul class="list-disc pl-5">
            <?php
            include 'db.php';
            $total_price = 0;
            $sql = "SELECT item_name, item_price, quantity FROM order_items WHERE order_id='$order_id'";
            $result = $conn->query($sql);

            while($row = $result->fetch_assoc()) {
                $total_price += $row['item_price'] * $row['quantity'];
                echo "<li>" . $row['item_name'] . " - Rp " . number_format($row['item_price'], 0, ',', '.') . " x " . $row['quantity'] . "</li>";
            }

            $conn->close();
            ?>
            </ul>
            <hr class="mt-4 mb-4">
            <p class="text-right font-bold">Total Harga: Rp <?php echo number_format($total_price, 0, ',', '.'); ?></p>
        </div>

        <div class="flex justify-center space-x-4 mt-8">
            <a href="index.html" class="bg-red-500 text-white px-4 py-2 rounded">Home</a>
        </div>
    </div>
</body>
</html>
