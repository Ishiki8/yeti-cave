<?php
date_default_timezone_set('Asia/Yekaterinburg');

/**
 * Конвертирует число в стоимость
 *
 * Преобразует переданное число в формат стоимости в рублях. Например, 1000 -> 1 000 ₽
 *
 * @param int $number исходное число
 *
 * @return string число, преобразованное в стоимость
 */
function convertNumberToPrice(int $number): string {
    return number_format($number, 0, '', ' ').' ₽';
}

CONST HOURS_IN_DAY = 24;
CONST SEC_IN_HOURS = 3600;
CONST MIN_IN_HOURS = 60;
CONST SEC_IN_MINUTES = 60;

/**
 * Считает оставшееся до наступления даты время
 *
 * @param string $date дата
 *
 * @return array часы, минуты, секунды до наступления даты
 */
function leftTimeToDate(string $date): array {
    $left_time = strtotime($date) - time() + HOURS_IN_DAY * SEC_IN_HOURS;
    $hours = str_pad(floor($left_time / SEC_IN_HOURS), 2, '0', STR_PAD_LEFT);
    $minutes = str_pad(ceil(($left_time - $hours * SEC_IN_HOURS) / MIN_IN_HOURS), 2, '0', STR_PAD_LEFT);
    $seconds = str_pad($left_time - $hours * SEC_IN_HOURS - $minutes * SEC_IN_MINUTES + SEC_IN_MINUTES, 2, '0', STR_PAD_LEFT);

    return [$hours, $minutes, $seconds == '60' ? '59' : $seconds];
}

/**
 * Получает список активных лотов
 *
 * Список лотов из базы данных, дата окончания которых больше текущей, отсортированные по дате создания в порядке убывания
 *
 * @param mysqli $con соединение с базой данных
 *
 * @return array активные лоты из базы данных или пустой массив
 */
function getLotsList(mysqli $con): array {
    $sql = 'SELECT `L`.`id`, `L`.`title`, `start_price`, `image`, `C`.`title` AS `category_name`, `create_date`, `end_date`
            FROM `Lot` AS `L`
            INNER JOIN `Category` AS `C` ON `L`.`category_id` = `C`.`id`
            WHERE `end_date` >= CURRENT_DATE
            ORDER BY `create_date` DESC';
    $result = mysqli_query($con, $sql);

    if($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        print('Запрос не выполнен!');
        return [];
    }
}

/**
 * Получает список категорий
 *
 * @param mysqli $con соединение с базой данных
 *
 * @return array все категории из базы данных или пустой массив
 */
function getCategories(mysqli $con): array {
    $sql = 'SELECT * FROM `Category`';
    $result = mysqli_query($con, $sql);

    if($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        print('Запрос не выполнен!');
        return [];
    }
}

/**
 * Получает значение параметра запроса
 *
 * @param string $parameter параметр
 *
 * @return string значение параметра или пустое значение
 */
function getQueryParameter(string $parameter): string {
    return $_GET[$parameter] ?? '';
}

/**
 * Проверяет существование id лота в базе данных
 *
 * Проверяет существование id лота из параметра запроса в базе данных
 *
 * @param mysqli $con соединение с базой данных
 *
 * @return bool true - если id существует, false - если id не существует
 */
function checkLotByQueryId(mysqli $con): bool {
    $id = filter_input(INPUT_GET, 'id');
    $lots = getLotsList($con);
    $flag = false;

    foreach ($lots as $item) {
        if ($item['id'] == $id) {
            $flag = true;
            break;
        }
    }

    return $flag;
}

/**
 * Получает лот по его id
 *
 * @param mysqli $con соединение с базой данных
 *
 * @return array массив с данными лота или пустой массив
 */
function getLotById(mysqli $con): array {
    $id = filter_input(INPUT_GET, 'id');
    $sql = 'SELECT `Lot`.*, `C`.`title` AS `category_name`, `C`.`code` AS `category_code`
            FROM `Lot`
            INNER JOIN `Category` AS `C` ON `Lot`.`category_id` = `C`.`id`
            WHERE `Lot`.`id` ='.$id;
    $result = mysqli_query($con, $sql);

    if($result) {
        return mysqli_fetch_array($result, MYSQLI_ASSOC);
    }
    else {
        print('Запрос не выполнен!');
        return [];
    }
}

/**
 * Добавляет лот в базу данных
 *
 * @param mysqli $con соединение с базой данных
 * @param array $data массив с данными лота
 *
 * @return int id добавленного лота
 */
function addLotToDatabase(mysqli $con, array $data): int {
    $sql = 'INSERT INTO `Lot`(`title`, `description`, `image`, `start_price`, `end_date`,
            `step`, `author_id`, `category_id`)
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?)';
    $prepare_values = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($prepare_values, 'sssisiii',
        $data['lot-name'],
        $data['message'],
        $data['lot-img'],
        $data['lot-rate'],
        $data['lot-date'],
        $data['lot-step'],
        $data['author_id'],
        $data['category']
    );
    mysqli_stmt_execute($prepare_values);

    return mysqli_insert_id($con);
}

/**
 * Добавляет пользователя в базу данных
 *
 * @param mysqli $con соединение с базой данных
 * @param array $data массив с данными пользователя
 */
function addUserToDatabase(mysqli $con, array $data): void {
    $sql = 'INSERT INTO `User`(`email`, `name`, `password`, `contacts`)
            VALUES
            (?, ?, ?, ?)';
    $prepare_values = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($prepare_values, 'ssss',
        $data['email'],
        $data['name'],
        $data['password'],
        $data['message']
    );
    mysqli_stmt_execute($prepare_values);
}

/**
 * Выбирает пользователя из базы данных по его E-mail
 *
 * @param mysqli $con соединение с базой данных
 * @param string $email E-mail пользователя
 *
 * @return array данные пользователя
 */
function getUserByEmail(mysqli $con, string $email): array {
    $sql = 'SELECT * FROM `User`
            WHERE `email` = ?';
    $prepare_values = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($prepare_values, 's', $email);
    mysqli_stmt_execute($prepare_values);

    return mysqli_fetch_assoc(mysqli_stmt_get_result($prepare_values));
}

/**
 * Получает значение по ключу из массива $_POST
 *
 * @param string $name ключ
 *
 * @return string | int | float значение из массива или пустое значение
 */
function getPostVal(string $name): string | int | float {
    return $_POST[$name] ?? '';
}

/**
 * Проверяет заполнение поля
 *
 * @param string $name ключ поля в массиве $_POST
 * @param string $value строка ошибки
 *
 * @return string строка ошибки или пустое значение
 */
function validateFilled(string $name): string {
    if (empty($_POST[$name])) {
        return 'Поле должно быть заполнено';
    }

    return '';
}

function validateLotRate(array $errors): array {
    if (!filter_var($_POST['lot-rate'], FILTER_VALIDATE_INT)) {
        $errors['lot-rate'] = 'Введите целое число';
    }

    if ($_POST['lot-rate'] <= 0) {
        $errors['lot-rate'] = 'Начальная цена должна быть больше 0';
    }

    return $errors;
}

function validateLotStep(array $errors): array {
    if (!filter_var($_POST['lot-step'], FILTER_VALIDATE_INT)) {
        $errors['lot-step'] = 'Введите целое число';
    }

    if ($_POST['lot-step'] <= 0) {
        $errors['lot-step'] = 'Шаг ставки должен быть больше 0';
    }

    return $errors;
}

function validateLotDate(array $errors): array {
    if (!is_date_valid($_POST['lot-date'])) {
        $errors['lot-date'] = 'Дата должна быть в формате ГГГГ-ММ-ДД';
    }

    if (strtotime($_POST['lot-date']) < strtotime('now')) {
        $errors['lot-date'] = 'Дата должна быть не раньше, чем завтра';
    }

    return $errors;
}

/**
 * Проверяет формат введенного E-mail
 *
 * @param string $name ключ E-mail в массиве $_POST
 * @param string $value строка ошибки
 *
 * @return string строка ошибки или пустое значение
 */
function validateEmail(array $errors): array{
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный формат email';
    }

    return $errors;
}

function isEmailUnique(mysqli $con, string $email): bool {
    $sql = 'SELECT `email` FROM `User`';
    $result = mysqli_query($con, $sql);
    $emails = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach($emails as $obj) {
        if ($obj['email'] == $email) {
            return false;
        }
    }

    return true;
}

function findLotsBySearchQuery(mysqli $con, string $searchQuery = ""): array | null {
    $sql = 'SELECT `L`.`id`, `L`.`title`, `start_price`, `image`, `C`.`title` AS `category_name`, `create_date`, `end_date`
            FROM `Lot` AS `L`
            INNER JOIN `Category` AS `C` ON `L`.`category_id` = `C`.`id`
            WHERE `end_date` >= CURRENT_DATE AND MATCH(`L`.`title`, `description`) AGAINST(?)
            ORDER BY `create_date` DESC';
    $prepare_values = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($prepare_values, 's', $searchQuery);
    mysqli_stmt_execute($prepare_values);

    return mysqli_fetch_all(mysqli_stmt_get_result($prepare_values), MYSQLI_ASSOC);
}

function getLotsForPagination(mysqli $con, int $lotsPerPage, string $searchQuery = ""): array {
    $offset = (intval(getQueryParameter('page')) - 1) * $lotsPerPage;

    if (empty($searchQuery)) {
        $sql = 'SELECT `L`.`id`, `L`.`title`, `start_price`, `image`, `C`.`title` AS `category_name`, `create_date`, `end_date`
        FROM `Lot` AS `L`
        INNER JOIN `Category` AS `C` ON `L`.`category_id` = `C`.`id`
        WHERE `end_date` >= CURRENT_DATE
        ORDER BY `create_date` DESC
        LIMIT ? OFFSET ?';

        $prepare_values = mysqli_prepare($con, $sql);
                
        mysqli_stmt_bind_param($prepare_values, 'ii',
            $lotsPerPage,
            $offset
        );
    } else {
        $sql = 'SELECT `L`.`id`, `L`.`title`, `start_price`, `image`, `C`.`title` AS `category_name`, `create_date`, `end_date`
        FROM `Lot` AS `L`
        INNER JOIN `Category` AS `C` ON `L`.`category_id` = `C`.`id`
        WHERE `end_date` >= CURRENT_DATE AND MATCH(`L`.`title`, `description`) AGAINST(?)
        ORDER BY `create_date` DESC
        LIMIT ? OFFSET ?';

        $prepare_values = mysqli_prepare($con, $sql);

        mysqli_stmt_bind_param($prepare_values, 'sii',
            $searchQuery,
            $lotsPerPage,
            $offset
        );
    } 
    
    mysqli_stmt_execute($prepare_values);
    return mysqli_fetch_all(mysqli_stmt_get_result($prepare_values), MYSQLI_ASSOC);
}