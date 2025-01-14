<?php
include 'db.php';

$categories = [
    'Aneka masakan ayam' => [],
    'Aneka masakan sapi' => [],
    'Aneka masakan Sayuran' => [],
    'Aneka minuman panas' => [],
    'Aneka minuman dingin' => []
];

$sql = "SELECT * FROM menu";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
    if (strpos($row['name'], 'Aneka masakan ayam') === false && strpos($row['name'], 'ayam') !== false) {
        $categories['Aneka masakan ayam'][] = $row;
    } elseif (strpos($row['name'], 'Aneka masakan sapi') === false && strpos($row['name'], 'sapi') !== false) {
        $categories['Aneka masakan sapi'][] = $row;
    } elseif (strpos($row['name'], 'Aneka masakan Sayuran') === false && (strpos($row['name'], 'Sayuran') !== false || strpos($row['name'], 'capcay') !== false || strpos($row['name'], 'cah kangkung') !== false)) {
        $categories['Aneka masakan Sayuran'][] = $row;
    } elseif (strpos($row['name'], 'Aneka minuman panas') === false && strpos($row['name'], 'panas') !== false) {
        $categories['Aneka minuman panas'][] = $row;
    } elseif (strpos($row['name'], 'Aneka minuman dingin') === false && (strpos($row['name'], 'dingin') !== false || strpos($row['name'], 'es') !== false)) {
        $categories['Aneka minuman dingin'][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('images/menu_bg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        h1, h2 {
            font-family: 'Lobster', cursive;
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
    <div class="container mx-auto p-4">
        <div class="overlay">
            <h1 class="text-4xl font-bold text-center mb-8">Menu</h1>

            <?php foreach ($categories as $category => $items) { ?>
                <h2 class="text-2xl font-semibold mb-4"><?php echo $category; ?></h2>
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-800 text-yellow-400">
                        <tr>
                            <th class="w-1/3 py-2 px-4">Nama</th>
                            <th class="w-1/4 py-2 px-4">Harga</th>
                            <th class="w-1/2 py-2 px-4">Deskripsi</th>
                            <th class="w-1/6 py-2 px-4">Kuantitas</th>
                            <th class="w-1/6 py-2 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $row) { ?>
                        <tr class="bg-gray-100">
                            <td class="border px-4 py-2"><?php echo $row['name']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['price']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['description']; ?></td>
                            <td class="border px-4 py-2">
                                <form action="cart.php" method="POST" class="inline">
                                    <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="item_name" value="<?php echo $row['name']; ?>">
                                    <input type="hidden" name="item_price" value="<?php echo $row['price']; ?>">
                                    <input type="number" name="quantity" value="1" min="1" class="w-16 text-center border rounded">
                            </td>
                            <td class="border px-4 py-2">
                                    <button type="submit" name="add_to_cart" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah ke Keranjang</button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>

            <div class="flex justify-center space-x-4 mt-8">
                <a href="index.html" class="bg-red-500 text-white px-4 py-2 rounded">Home</a>
            </div>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
