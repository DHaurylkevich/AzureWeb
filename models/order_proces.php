<?php
session_start();

if (isset($_POST['submit']) && isset($_SESSION['cart'])){
  $name = $_POST['name'];
    $fields_required = ["name", "surname", "tel", "address", "email", "id_code"];
    $errors = [];

    //empty
    foreach ($fields_required as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "Pole <b>$field</b> jest wymagane";
        }
    }

    if (!empty($errors)) {
        $_SESSION["error_message"] = implode("<br>", $errors);
        header("Location: ../views/order_form.php");
        exit();
    }

    //email
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Niepoprawna wartość";
        $_SESSION['error_message'] = $errorMessage;
        header("Location: ../views/order_form.php?error=" . urlencode($errorMessage));
        exit();
    }

    $name = htmlspecialchars(trim($_POST['name']));
    $surname = htmlspecialchars(trim($_POST['surname']));
    $tel = htmlspecialchars(trim($_POST['tel']));
    $address = htmlspecialchars(trim($_POST['address']));
    $email = htmlspecialchars(trim($email));
    $id_code = htmlspecialchars(trim($_POST['id_code']));


    $id_products = implode(',', array_keys($_SESSION['cart']));

    if(!isset($_SESSION['id_users'])){
        $id_user= 0000;
    }else {
        $id_user = $_SESSION['id_users'];
    }

    unset($_SESSION['cart']);
    $message = "Thank you for ordering id#";
    header("Location: ../views/complete_order.php?success=" . urlencode($message));
    exit();
}else{
    $message = "Cart is empty!";
    header("Location: ../views/cart_form.php?error=" . urlencode($message));
    exit();
}
?>
