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
        echo("2");
        $_SESSION["error_message"] = "Niepoprawy adress";
        header("Location: registration_form.php");
        exit();
    }

    $apiUrl = "https://testspring69.azurewebsites.net/users/byEmail/". urlencode($email);
    $response = file_get_contents($apiUrl);

    // Декодируем JSON-ответ в ассоциативный массив
    $emailList = json_decode($response, true);
    echo $emailList;

    try{
        if (!empty($emailList)) {
            echo "Password from form: " . $_POST['password'] . "<br>";
            echo "Hashed password from database: " . $emailList['password'] . "<br>";
                    if (password_verify($_POST['password'], $emailList['password'])) {
                        $_SESSION['id'] = session_id();
                        $_SESSION['id_users'] = $emailList['id'];
                        $_SESSION['username'] = $emailList['username'];
                        $_SESSION['email'] = $emailList['email'];
                        $_SESSION['user_form'] = $emailList['user_type'];
                        header("Location: ../views/user_page.php");
                        exit();
                    } else {
                        echo("6");
                        $_SESSION["error_message"] = "Błędny login lub hasło!";
                        header("Location: https://webcdv.azurewebsites.net/views/login_form.php");
                        exit();
                    }
        } else {
            echo("3");
            $_SESSION["error_message"] = "Błędny login lub hasło!";
            header("Location: https://webcdv.azurewebsites.net/views/login_form.php");
            exit();
        }
    } catch (PDOException $e) {
        echo("4");
        // Обработка ошибок базы данных
        $_SESSION["error_message"] = "Error BD: " . $e->getMessage();
        header("Location: https://webcdv.azurewebsites.net/views/error_page.php");
        exit();
    }
} else {
    echo("5");
    header("Location: https://webcdv.azurewebsites.net/views/login_form.php");
    exit();
}