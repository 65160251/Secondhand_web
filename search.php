<?php
session_start();
include 'database/db.php'; 

$query = isset($_GET['query']) ? $_GET['query'] : '';

if ($query) {
    $sql = "SELECT * FROM products WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_term = "%$query%";
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Search Results</h1>
        <a href="index.php">Back to Homepage</a>
    </header>
    <main>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($product = $result->fetch_assoc()): ?>
                <div>
                    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                    <?php if (!empty($product['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="max-width: 200px;">
                    <?php else: ?>
                        <p>No image available.</p>
                    <?php endif; ?>
                    <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                    <a href="products/product_detail.php?id=<?php echo $product['id']; ?>">View Details</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </main>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
