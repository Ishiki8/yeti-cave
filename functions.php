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

    return [$hours, $minutes];
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
    $sql = 'SELECT `L`.`id`, `L`.`title`, `start_price`, `image`, `C`.`title` AS `category_name`, 
            `C`.`code` AS `category_code`, `create_date`, `end_date`
            FROM `Lot` AS `L`
            INNER JOIN `Category` AS `C` ON `L`.`category_id` = `C`.`id`
            WHERE `end_date` >= CURRENT_DATE
            ORDER BY `create_date` DESC';
    $result = mysqli_query($con, $sql);

    if ($result) {
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

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        print('Запрос не выполнен!');
        return [];
    }
}

/**
 * Получает название категории по параметру запроса
 *
 * @param mysqli $con соединение с базой данных
 *
 * @return string название категории или пустую строку
 */
function getCategoryNameByQueryParameter(mysqli $con): string {
    $sql = 'SELECT `title` FROM `Category`
            WHERE `code` = ?';

    $prepare_values = mysqli_prepare($con, $sql);
    $queryParameter = getQueryParameter('category');

    mysqli_stmt_bind_param($prepare_values, 's',
        $queryParameter
    );
    mysqli_stmt_execute($prepare_values);

    $result = mysqli_fetch_row(mysqli_stmt_get_result($prepare_values));
    return $result[0] ?? '';
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
 * Формирует адрес для пагинации
 *
 * @param int $page номер страницы для перенаправления
 *
 * @return string адрес для перенаправления
 */
function getUrlForPagination(int $page): string {
    $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $url = explode('?', $url);
    $url = $url[0];

    $parameters = [];
    parse_str($_SERVER['QUERY_STRING'], $parameters);

    $parameters['page'] = $page;

    return $url . "?" . http_build_query($parameters);
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

    $sql = 'SELECT COUNT(*) FROM `Lot`
            WHERE `id` = '.$id;

    $result = mysqli_query($con, $sql);
    return mysqli_fetch_row($result)[0];
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

    return mysqli_fetch_assoc(mysqli_stmt_get_result($prepare_values)) ?? [];
}

/**
 * Получает значение по ключу из массива $_POST
 *
 * @param string $key ключ
 *
 * @return string | int | float значение из массива или пустое значение
 */
function getPostVal(string $key): string | int | float {
    return isset($_POST[$key]) ? htmlspecialchars($_POST[$key]) : '';
}

/**
 * Проверяет число из массива $_POST на корректность
 *
 * Число корректно, если оно целое и больше 0
 *
 * @param array $errors массив ошибок
 *
 * @param string $key ключ в массиве $_POST
 *
 * @return array массив ошибок с ошибкой, если она есть
 */
function validateLotInt(array $errors, string $key): array {
    if (!filter_var($_POST[$key], FILTER_VALIDATE_INT, array('options' => array('min_range' => 0)))) {
        $errors[$key] = 'Введите целое число больше 0';
    }

    return $errors;
}

/**
 * Проверяет дату завершения торгов на корректность
 *
 * Дата корректна, если она находится в формате ГГГГ-ММ-ДД и больше текущей
 *
 * @param array $errors массив ошибок
 *
 * @return array массив ошибок с ошибкой, если она есть
 */
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
 * @param array $errors массив ошибок
 *
 * @return array массив ошибок с ошибкой, если она есть
 */
function validateEmail(array $errors): array {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный формат email';
    }

    return $errors;
}

/**
 * Считает количество активных лотов по параметру запроса
 *
 * Если передан параметр 'search', то считаются активные лоты, удовлетворяющие значению параметра 'search'
 *
 * Если передан параметр 'category', то считаются активные лоты с категорией из параметра 'category'
 *
 * @param mysqli $con соединение с базой данных
 *
 * @param string $queryParameter параметр запроса - 'search' или 'category'
 *
 * @return int количество активных лотов
 */
function countLotsByQueryParameter(mysqli $con, string $queryParameter): int {
    $parameterValue = getQueryParameter($queryParameter);

    if ($queryParameter === 'search') {
        $sql = 'SELECT COUNT(*) FROM `Lot`
                WHERE `end_date` >= CURRENT_DATE 
                AND MATCH(`title`, `description`) AGAINST(?)';
    } else if ($queryParameter === 'category') {
        $sql = 'SELECT COUNT(*) FROM `Lot` AS `L`
                INNER JOIN `Category` AS `C` ON `L`.`category_id` = `C`.`id`
                WHERE `end_date` >= CURRENT_DATE 
                AND `C`.`code` = ?';
    } else {
        return 0;
    }

    $prepare_values = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($prepare_values, 's', $parameterValue);
    mysqli_stmt_execute($prepare_values);
    $result = mysqli_stmt_get_result($prepare_values);

    return mysqli_fetch_row($result)[0] ?? 0;
}

/**
 * Получает лоты по параметру поиска
 *
 * @param mysqli $con соединение с базой данных
 *
 * @param int $lotsPerPage количество лотов для отображения на странице
 *
 * @param string $searchQuery значение параметра 'search'
 *
 * @return array активные лоты, удовлетворяющие значению параметра 'search'
 */
function getLotsBySearchQuery(mysqli $con, int $lotsPerPage, string $searchQuery): array {
    $offset = (intval(getQueryParameter('page')) - 1) * $lotsPerPage;

    $sql = 'SELECT `L`.`id`, `L`.`title`, `start_price`, `image`, `C`.`title` AS `category_name`,
            `C`.`code` AS `category_code`, `create_date`, `end_date`
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
    mysqli_stmt_execute($prepare_values);

    return mysqli_fetch_all(mysqli_stmt_get_result($prepare_values), MYSQLI_ASSOC);
}

/**
 * Получает лоты по параметру категории
 *
 * @param mysqli $con соединение с базой данных
 *
 * @param int $lotsPerPage количество лотов для отображения на странице
 *
 * @param string $categoryCode значение параметра 'category'
 *
 * @return array лоты, категория которых удовлетворяет значению параметра 'category'
 */
function getLotsByCategoryCode(mysqli $con, int $lotsPerPage, string $categoryCode): array {
    $offset = (intval(getQueryParameter('page')) - 1) * $lotsPerPage;

    $sql = 'SELECT `L`.`id`, `L`.`title`, `start_price`, `image`, `C`.`title` AS `category_name`, 
            `C`.`code` AS `category_code`, `create_date`, `end_date`
            FROM `Lot` AS `L`
            INNER JOIN `Category` AS `C` ON `L`.`category_id` = `C`.`id`
            WHERE `end_date` >= CURRENT_DATE AND `C`.`code` = ?
            ORDER BY `create_date` DESC
            LIMIT ? OFFSET ?';
    $prepare_values = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($prepare_values, 'sii',
        $categoryCode,
        $lotsPerPage,
        $offset
    );
    mysqli_stmt_execute($prepare_values);

    return mysqli_fetch_all(mysqli_stmt_get_result($prepare_values), MYSQLI_ASSOC);
}