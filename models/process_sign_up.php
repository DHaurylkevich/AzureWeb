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

    $context = stream_context_create($options);
// Инициализация cURL-сессии
    $ch = curl_init($springApiUrl);
// Установка параметров запроса
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($springData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Выполнение запроса
    $result = curl_exec($ch);

// Закрытие cURL-сессии
    curl_close($ch);

// Добавьте этот код для отладки
    echo "Spring API URL: $springApiUrl<br>";
    echo "Spring Data: " . json_encode($springData) . "<br>";
    echo "Result from Spring API: " . $result . "<br>";

    if ($result === FALSE) {
        $_SESSION["error_message"] = "Nie udało się zarejestrować użytkownika";
        echo "3";
        header("Location: ../views/registration_form.php");
        exit();
    }


    $resultData = json_decode($result, true);

    // Проверка ответа от Spring-сервера
    $_SESSION['id'] = session_id();
    $_SESSION['id_users'] = $resultData['data']['id'];
    $_SESSION['username'] = $resultData['data']['username'];
    $_SESSION['user_type'] = $resultData['data']['user_type'];

    echo "user type ". $resultData['data']['user_type'];

    if ($_SESSION['user_type'] == "user") {
        echo "1";
        header("Location: ../views/user_page.php");
        exit();
    }
    $_SESSION["error_message"] = "Nie udało się zarejestrować użytkownika: " . $resultData['message'];
    header("Location: ../views/registration_form.php");
    echo "2";
    exit();
}
?>
