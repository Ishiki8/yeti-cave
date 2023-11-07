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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $required_fields = [
            'email' => 'Введите e-mail',
            'password' => 'Введите пароль'
        ];

        foreach($required_fields as $key => $value) {
            if (empty($_POST[$key])) {
                $errors[$key] = $value;
            }
        }

        if (!isset($errors['email'])) {
            $errors = validateEmail($errors);
        }

        if (!isset($errors['email']) && isEmailUnique($con, $_POST['email'])) {
            $errors['email'] = 'Пользователя с таким E-mail не существует';
        }

        if (!isset($errors['email']) && !isset($errors['password'])) {
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
