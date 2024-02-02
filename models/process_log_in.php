<?php
session_start();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(empty($_POST['email']) || empty($_POST['password'])){
        $_SESSION["error_message"] = "Wypełni pola wymagane!";
        header("Location: ../views/login_form.php");
        exit();
    }

    //email
    $email = $_POST["email"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["error_message"] = "Niepoprawy adress";
        header("Location: registration_form.php");
        exit();
    }

    $apiUrl = "https://testspring69.azurewebsites.net/users/byEmail/". urlencode($email);
    $response = file_get_contents($apiUrl);

    // Декодируем JSON-ответ в ассоциативный массив
    $emailList = json_decode($response, true);

    try{
        if (!empty($emailList)) {
                    if (password_verify($_POST['password'], $emailList['password'])) {
                        $_SESSION['id'] = session_id();
                        $_SESSION['id_users'] = $emailList['id'];
                        $_SESSION['username'] = $emailList['username'];
                        $_SESSION['email'] = $emailList['email'];
                        $_SESSION['user_form'] = $emailList['user_type'];
                        header("Location: ../views/user_page.php");
                    } else {
                        $_SESSION["error_message"] = "Błędny login lub hasło!";
                        header("Location: https://webcdv.azurewebsites.net/views/login_form.php");
                    }
        } else {
            $_SESSION["error_message"] = "Błędny login lub hasło!";
            header("Location: https://webcdv.azurewebsites.net/views/login_form.php");
        }
        exit();
    } catch (PDOException $e) {
        // Обработка ошибок базы данных
        $_SESSION["error_message"] = "Error BD: " . $e->getMessage();
        header("Location: https://webcdv.azurewebsites.net/views/error_page.php");
        exit();
    }
} else {
    header("Location: https://webcdv.azurewebsites.net/views/login_form.php");
    exit();
}