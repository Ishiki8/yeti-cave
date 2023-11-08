<?php
require_once('init.php');
require_once('helpers.php');
require_once('functions.php');

$header = include_template('header.php', [
    'categories' => getCategories($con),
]);

$footer = include_template('footer.php', [
    'categories' => getCategories($con),
]);

if (isset($_SESSION['username'])) {
    http_response_code(403);

    $registration_content = include_template('403.php', [
        'title' => 'Доступ запрещен',
        'header' => $header,
        'footer' => $footer,
    ]);
} else {
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $required_fields = [
            'email' => 'Введите e-mail',
            'password' => 'Введите пароль',
            'name' => 'Введите имя',
            'message' => 'Напишите, как с вами связаться'
        ];

        foreach($required_fields as $key => $value) {
            if (empty($_POST[$key])) {
                $errors[$key] = $value;
            }
        }

        if (!isset($errors['email'])) {
            $errors = validateEmail($errors);
        }

        if (!isset($errors['email']) && !empty(getUserByEmail($con, $_POST['email']))) {
            $errors['email'] = 'E-mail уже используется';
        }

        $errors = array_filter($errors);

        if(!$errors){
            $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $data = $_POST;
            $data['password'] = $passwordHash;

            addUserToDatabase($con, $data);

            header('Location: /login.php');
            exit;
        }
    }

    $registration_content = include_template('registration.php', [
        'header' => $header,
        'footer' => $footer,
        'errors' => $errors,
    ]);
}

print($registration_content);