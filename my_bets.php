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

$userBets = getBetsForUser($con);

$myBetsContent = include_template('my_bets.php', [
    'header' => $header,
    'footer' => $footer,
    'userBets' => $userBets,
    'con' => $con,
]);

print($myBetsContent);