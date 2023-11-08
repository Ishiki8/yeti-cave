<?php
require_once('init.php');
require_once('helpers.php');
require_once('functions.php');

$header = include_template('header.php',[
    'categories' => getCategories($con),
]);

$footer = include_template('footer.php', [
    'categories' => getCategories($con),
]);

if (!getQueryParameter('id') || !checkLotByQueryId($con)) {
    http_response_code(404);

    $lot_content = include_template('404.php',[
        'title' => 'Страница не найдена',
        'header' => $header,
        'footer' => $footer,
    ]);
} else {
    $lot_content = include_template('lot.php', [
        'lot' => getLotById($con),
        'header' => $header,
        'footer' => $footer,
    ]);
}

print($lot_content);
