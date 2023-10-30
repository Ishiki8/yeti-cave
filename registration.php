<?php
require_once('init.php');
require_once('helpers.php');
require_once('functions.php');

$header = include_template('header.php', [
    'categories' => getCategories($con),
    'is_auth' => $is_auth,
    'user_name' => $user_name,
]);

$footer = include_template('footer.php', [
    'categories' => getCategories($con),
]);

if ($is_auth) {
    http_response_code(403);

    $registration_content = include_template('403.php', [
        'title' => 'Доступ запрещен',
        'header' => $header,
        'footer' => $footer,
    ]);
} else {
    $errors = [];
    $rules = [
        'email' => function() {
            return validateFilled('email', 'Введите e-mail');
        },
        'password' => function() {
            return validateFilled('password', 'Введите пароль');
        },
        'name' => function() {
            return validateFilled('name', 'Введите имя');
        },
        'message' => function() {
            return validateFilled('message', 'Напишите как с вами связаться');
        }
    ];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        foreach($_POST as $key => $value) {
            if (isset($rules[$key])) {
                $rule = $rules[$key];
                $errors[$key] = $rule();
            }
        }

        $emailValidation = validateEmail('email', 'Некорректный формат e-mail');

        if (!$errors['email'] && $emailValidation) {
            $errors['email'] = $emailValidation;
        }

        if (!$errors['email']) {
            $errors['email'] = validateUniqueEmail($con, $_POST['email'], 'E-mail уже используется');
        }

        $errors = array_filter($errors);

        if(!$errors){
            $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $data = $_POST;
            $data['password'] = $passwordHash;

            addUserToDatabase($con, $data);

            header('Location: /login.php');
        }
    }

    $registration_content = include_template('registration.php', [
        'header' => $header,
        'footer' => $footer,
        'errors' => $errors,
    ]);
}

print($registration_content);