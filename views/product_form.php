<?php
session_start();


if (isset($_GET['action']) && $_GET['action'] == "add") {
    $id = intval($_GET['id']);

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity']++;
        header("Location: product_form.php?id=$id");
        exit();
    } else {
        $stmt = $dbh->query("SELECT * FROM products WHERE id = $id");

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $_SESSION['cart'][$row[0]['id']] = array(
                "quantity" => 1,
                "price" => $row[0]['price']
            );
            header("Location: product_form.php?id=$id");
            exit();
        } else {
            echo("Error");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>RED</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles_elements/css/styles.css">
    <link rel="stylesheet" href="../styles_elements/css/product.css">
</head>

<body>
<header id="header">
    <!-- Header content here... -->
</header>

<main>
    <div class="category">
        <?php
        $apiUrl = "https://testspring69.azurewebsites.net/categories";
        $response = file_get_contents($apiUrl);

        $listCategorys = json_decode($response, true);

        foreach ($listCategorys as $listCategory) {
            ?>
            <a href="../index.php?category=<?= $listCategory['name'] ?>" class="category-item"><?= $listCategory['name'] ?></a>
        <?php } ?>
    </div>
    <div class="product-border">
        <?php
        $productid = $_GET['id'];

        $apiUrl = "https://testspring69.azurewebsites.net/products/" . $productid;
        $response = file_get_contents($apiUrl);

        $product = json_decode($response, true);
        ?>

        <div class="product-pictures">
            <div class="main-photo ">
                <img class="main-photo" src="<?= $product['image'] ?>">
            </div>
        </div>

        <div class="product-info">
            <div class="product-name">
                <h3><?= $product['title'] ?></h3>
                <p><?= $product['price'] ?> zł</p>
            </div>
            <div class="description">
                <p><?= $product['description'] ?></p>
            </div>

            <div class="product-size">
                <?php
                $sizeString = $product['size'];
                $sizes = explode(',', $sizeString);
                foreach ($sizes as $size) {
                    echo '<p>' . trim($size) . '</p>';
                }
                ?>
            </div>

            <div class="buttons">
                <a href="product_form.php?action=add&id=<?= $productid ?>">
                    <button class="buy-add button-buy">
                        <p>Buy</p>
                    </button>
                </a>
                <button class="buy-add button-add">
                    <p>Add</p>
                </button>
            </div>
        </div>
    </div>
    <div> </div>
</main>

<footer class="footer">
    <!-- Footer content here... -->
</footer>

<script src="../script.js"></script>
</body>

</html>
