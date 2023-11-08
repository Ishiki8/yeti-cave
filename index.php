<?php
require_once('init.php');
require_once('helpers.php');
require_once('functions.php');

$header = include_template('header.php');

$lotsListContent = include_template('lotsList.php', [
    'lots' => getLotsList($con),
]);

$main = include_template('main.php', [
    'categories' => getCategories($con),
    'lotsListContent' => $lotsListContent,
]);

$footer = include_template('footer.php', [
    'categories' => getCategories($con),
]);

$layout_content = include_template('layout.php', [
    'title' => 'Главная',
    'header' => $header,
    'main' => $main,
    'footer' => $footer,
    'categories' => getCategories($con),
]);

print($layout_content);

