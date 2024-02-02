<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fields_required = ["username", "email", "password", "cpassword"];
    $errors = [];

    foreach ($fields_required as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "Pole <b>$field</b> jest wymagane";
        }
    }

    if (!empty($errors)) {
        $_SESSION["error_message"] = implode("<br>", $errors);
        header("Location: ../views/registration_form.php");
        exit();
    }

    $email = $_POST["email"];

    //Pass
    if ($_POST["password"] != $_POST["cpassword"]) {
        $_SESSION["error_message"] = "Hasła muszą być identyczne";
        header("Location: ../views/registration_form.php");
        exit();
    }

    //Username
    $username = htmlspecialchars(trim($_POST["username"]));
// Отправка данных на Spring-сервер
    $springApiUrl = "https://testspring69.azurewebsites.net/users/create";
    $springData = [
        'username' => $username,
        'email' => $_POST["email"],
        'password' => $_POST["password"],
        'user_type' => "user"
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($springData)
        ]
    ];

// Выполнение запроса
    $context = stream_context_create($options);
    $result = file_get_contents($springApiUrl, false, $context);

// Добавьте этот код для отладки
    echo "Spring API URL: $springApiUrl<br>";
    echo "Spring Data: " . json_encode($springData) . "<br>";
    echo "Result from Spring API: " . $result . "<br>";


    if ($result === FALSE) {
        $_SESSION["error_message"] = "Nie udało się zarejestrować użytkownika";
        header("Location: ../views/registration_form.php");
        exit();
    }


    $resultData = json_decode($result, true);

    if(!Empty($result)) {
        $_SESSION['id'] = session_id();
        $_SESSION['id_users'] = $resultData['id'];
        $_SESSION['username'] = $resultData['username'];
        $_SESSION['user_type'] = $resultData['user_type'];

        echo "id " . $_SESSION['id_users'];
        echo "name " . $_SESSION['username'];
        echo "user type " . $_SESSION['user_type'];


        if ($_SESSION['user_type'] == "user") {
            header("Location: ../views/user_page.php");
            exit();
        }
    }else {
        $_SESSION["error_message"] = "Nie udało się zarejestrować użytkownika: " . $resultData['message'];
        header("Location: ../views/registration_form.php");
        exit();
    }
}
?>
