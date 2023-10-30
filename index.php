<?php
require_once('init.php');
require_once('helpers.php');
require_once('functions.php');

$header = include_template('header.php',[
    'is_auth' => $is_auth,
    'user_name' => $user_name,
]);

$main = include_template('main.php', [
    'categories' => getCategories($con),
    'lots' => getLotsList($con),
]);

$footer = include_template('footer.php', [
    'categories' => getCategories($con),
]);

$layout_content = include_template('layout.php', [
    'title' => 'Главная',
    'header' => $header,
    'main' => $main,
    'footer' => $footer,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => getCategories($con),
]);

print($layout_content);

