<?php
session_start();

require_once "../config/config.php";

if (isset($_GET['action']) && $_GET['action'] == "add") {

    $id = intval($_GET['id']);

    if (isset($_SESSION['cart'][$id])) {

        $_SESSION['cart'][$id]['quantity']++;
        header("Location: product_form.php?id=$id");
        exit();
    } else {
        $apiUrl = "https://testspring69.azurewebsites.net/products/" . urlencode($id);
        $response = file_get_contents($apiUrl);

        $productData = json_decode($response, true);

        if (!empty($productData)) {
            $_SESSION['cart'][$productData['id']] = array(
                "quantity" => 1,
                "price" => $productData['price']
            );
            header("Location: product_form.php?id=$id");
            exit();
        } else {
            $_SESSION['error_message'] = "Error fetching product data from Spring API";
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
    <div class="header-menu">
        <a href="../index.php"><img id="logo"
                                    src="https://logos-world.net/wp-content/uploads/2022/01/Playboi-Carti-Emblem.png"></a>
        <div class="header-menu-item "><a href="News.php">News</a> </div>
        <div class="header-menu-item"><a href="Sales.php">Sales</a> </div>
        <div class="header-menu-item "><a href="FAQ.php">FAQ</a> </div>
        <div class="header-menu-item "><a href="Contacts.php">Contacts</a> </div>
        <div class="account"><a href="user_page.php"><img
                        src="https://cdn-icons-png.flaticon.com/512/1250/1250689.png"></a> </div>
        <div class="total">
            <a href="cart_form.php">
                <img src="https://cdn-icons-png.flaticon.com/512/1374/1374128.png">
                <?php
                $totalQuantity = 0;
                if(isset($_SESSION['cart'])){
                    foreach($_SESSION['cart'] as $item){
                        $totalQuantity += $item['quantity'];
                    }
                }
                echo $totalQuantity;
                ?>
            </a>
        </div>
    </div>
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
        <?php }?>
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
                <img class="main-photo"
                     src="<?= $product['image']?>">
            </div>
        </div>

        <div class="product-info">
            <div class="product-name">
                <h3><?=$product['title']?></h3>
                <p><?=$product['price']?> zł</p>
            </div>
            <div class="description">
                <p><?=$product['description']?></p>
            </div>

            <div class="product-size">
                <?php
                $sizeString = $product['size'];
                $sizes = explode(',', $sizeString);
                foreach ($sizes as $size) {
                    echo '<p>'.trim($size).'</p>';
                }
                ?>

            <div class="buttons">
                <a href="product_form.php?action=add&id=<?=$productid?>">
                    <button class="buy-add button-buy">
                        <p>Buy</p>
                    </button>
                </a>
                <a href="product_form.php?action=add&id=<?=$productid?>">
                <button class="buy-add button-add">
                    <p>Add</p>
                </button>
                </a>
            </div>
        </div>
        </div>
    </div>
    <div> </div>
</main>
<footer class="footer">
    <div class="footer-content">
        <div class="footer-copyright">©&nbsp;2023 Red.com</div>
    </div>
</footer>
<script src="../script.js"></script>
</body>

</html>