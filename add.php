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

if (!$is_auth) {
    http_response_code(403);

    $add_content = include_template('403.php', [
        'title' => 'Доступ запрещен',
        'header' => $header,
        'footer' => $footer,
    ]);
}
else {
    $errors = [];
    $rules = [
        'lot-name' => function () {
            return validateFilled('lot-name', 'Введите наименование лота');
        },
        'category' => function () {
            return validateDropdown('category', 'Выберите категорию');
        },
        'message' => function () {
            return validateFilled('message', 'Напишите описание лота');
        },
        'lot-rate' => function () {
            return validateFilled('lot-rate', 'Введите начальную цену');
        },
        'lot-step' => function () {
            return validateFilled('lot-step', 'Введите шаг ставки');
        },
        'lot-date' => function () {
            return validateFilled('lot-date', 'Введите дату завершения торгов');
        },
    ];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        foreach ($_POST as $key => $value) {
            if (isset($rules[$key])) {
                $rule = $rules[$key];
                $errors[$key] = $rule();
            }
        }

        $imgValidation = validateImg('lot-img', 'Загрузите изображение лота');

        if ($imgValidation) {
            $errors['lot-img'] = $imgValidation;
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_name = $_FILES['lot-img']['tmp_name'];
            $file_type = finfo_file($finfo, $file_name);

            if ($file_type !== 'image/jpeg' && $file_type !== 'image/png') {
                $errors['lot-img'] = 'Некорректный формат изображения';
            }
        }

        if (!$errors['lot-rate'] && (!is_numeric($_POST['lot-rate']) || stristr($_POST['lot-rate'], '.'))) {
            $errors['lot-rate'] = 'Цена должна быть целым числом!';
        }

        if (!$errors['lot-step'] && (!is_numeric($_POST['lot-step']) || stristr($_POST['lot-step'], '.'))) {
            $errors['lot-step'] = 'Шаг ставки должен быть целым числом!';
        }

        if (!$errors['lot-date']) {
            if (!is_date_valid($_POST['lot-date'])) {
                $errors['lot-date'] = 'Некорректный формат даты';
            }
        }

        $errors = array_filter($errors);

        if (!$errors) {
            $file_name = strval(time()) . $_FILES['lot-img']['name'];
            $file_path = __DIR__ . '/uploads/';
            $file_url = '/uploads/' . $file_name;
            move_uploaded_file($_FILES['lot-img']['tmp_name'], $file_path . $file_name);

            $data = $_POST;
            $data['author_id'] = $user_id;
            $data['lot-img'] = $file_url;
            $id = addLotToDatabase($con, $data);
            header('Location: /lot.php?id=' . $id);
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
