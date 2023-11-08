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

if (!isset($_SESSION['username'])) {
    http_response_code(403);

    $add_content = include_template('403.php', [
        'title' => 'Доступ запрещен',
        'header' => $header,
        'footer' => $footer,
    ]);
}
else {
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $required_fields = [
            'lot-name' => 'Введите наименование лота',
            'category' => 'Выберите категорию',
            'message' => 'Введите описание лота',
            'lot-rate' => 'Введите начальную цену',
            'lot-step' => 'Введите шаг ставки',
            'lot-date' => 'Введите дату окончания торгов',
        ];

        foreach($required_fields as $key => $value) {
            if (empty($_POST[$key])) {
                $errors[$key] = $value;
            }
        }

        if (isset($_FILES['lot-img']) && !empty($_FILES['lot-img']['tmp_name'])) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_name = $_FILES['lot-img']['tmp_name'];
            $file_type = finfo_file($finfo, $file_name);

            if ($file_type !== 'image/jpeg' && $file_type !== 'image/png') {
                $errors['lot-img'] = 'Некорректный формат изображения';
            }
        } else {
            $errors['lot-img'] = 'Загрузите изображение лота';
        }

        if (!isset($errors['lot-rate']) || $_POST['lot-rate'] === 0) {
            $errors = validateLotInt($errors, 'lot-rate');
        }

        if (!isset($errors['lot-step']) || $_POST['lot-step'] === 0) {
            $errors = validateLotInt($errors, 'lot-step');
        }

        if (!isset($errors['lot-date'])) {
            $errors = validateLotDate($errors);
        }

        $errors = array_filter($errors);

        if (!$errors) {
            $file_name = strval(time()) . $_FILES['lot-img']['name'];
            $file_path = __DIR__ . '/uploads/';
            $file_url = '/uploads/' . $file_name;
            move_uploaded_file($_FILES['lot-img']['tmp_name'], $file_path . $file_name);

            $data = $_POST;
            $data['author_id'] = $_SESSION['user_id'];
            $data['lot-img'] = $file_url;
            $id = addLotToDatabase($con, $data);
            header('Location: /lot.php?id=' . $id);
            exit;
        }
    };

    $add_content = include_template('add.php', [
        'categories' => getCategories($con),
        'errors' => $errors,
        'header' => $header,
        'footer' => $footer,
    ]);
}

print($add_content);
