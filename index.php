<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RED</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles_elements/css/styles.css">
</head>

<body>
<?php include 'views/header.php'; ?>
<main>
    <div class="category">
        <?php
        $apiUrl = "https://testspring69.azurewebsites.net/categories";
        $response = file_get_contents($apiUrl);

        $listCategorys = json_decode($response, true);

        foreach ($listCategorys as $listCategory) {
            ?><a href="index.php?action=on&category=<?= $listCategory['name'] ?>" class="category-item"><?= $listCategory['name'] ?></a>

        <?php }
        $category = $_GET['category'] ?? '';?>

    </div>
    <section>
        <h2><?=$category?></h2>
        <div class="products">
            <?php
            $apiUrl = "https://testspring69.azurewebsites.net/products?category=" .urlencode($category);
            $response = file_get_contents($apiUrl);

            $products = json_decode($response, true);

            foreach ($products as $product) {
                ?>
                <a href="views/product_form.php?id=<?= $product['id'] ?>">
                    <div class="product">
                        <img src="<?= $product['image'] ?>">
                        <h3><?= $product['title'] ?></h3>
                        <p><?= $product['price'] ?> zł</p>
                    </div>
                </a>
                <?php
            }
            ?>
        </div>
    </section>

</main>
<button onclick="scrollToTop()" id="scrollToTopBtn" class="scroll-to-top-button">▲</button>

<?php include 'views/footer.php'; ?>
<script>
    var header = document.getElementById('header');
    var prevScrollPos = window.pageYOffset;
    window.onscroll = function () {
        var currentScrollPos = window.pageYOffset;
        if (prevScrollPos > currentScrollPos || currentScrollPos < 35) {
            header.style.transform = 'translateY(0)';
        } else {
            header.style.transform = 'translateY(-100%)';
        }
        prevScrollPos = currentScrollPos;
    };
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    window.onscroll = function () { toggleScrollToTopButton() };
    function toggleScrollToTopButton() {
        var scrollToTopBtn = document.getElementById("scrollToTopBtn");
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 400) {
            scrollToTopBtn.classList.add("show");
        } else {
            scrollToTopBtn.classList.remove("show");
        }
    }
</script>
</body>
</html>