<?php
require_once('init.php');
require_once('helpers.php');
require_once('functions.php');

$header = include_template('header.php',[
    'categories' => getCategories($con),
    'is_auth' => $is_auth,
    'user_name' => $user_name,
]);

$footer = include_template('footer.php', [
    'categories' => getCategories($con),
]);

if ($is_auth) {
    header('location: /index.php');
} else {
    $errors = [];
    $rules = [
        'email' => function() {
            return validateFilled('email', 'Введите e-mail');
        },
        'password' => function() {
            return validateFilled('password', 'Введите пароль');
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

        $email = validateUniqueEmail($con, $_POST['email'], 'E-mail уже используется');

        if (!$email && !$errors['email']) {
            $errors['email'] = 'Пользователя с таким E-mail не существует';
        }

        if (!$errors['email'] && !$errors['password']) {
            $user = getUserByEmail($con, $_POST['email']);

            if (!password_verify($_POST['password'], $user['password'])) {
                $errors['password'] = 'Неправильный пароль';
            }

            $errors = array_filter($errors);

            if (!$errors) {
                session_start();
                $_SESSION['username'] = $user['name'];
                $_SESSION['user_id'] = $user['id'];
                header('Location: /index.php');
            }
        }
    }

    $login_content = include_template('login.php', [
        'header' => $header,
        'footer' => $footer,
        'errors' => $errors,
    ]);

    print($login_content);
}
