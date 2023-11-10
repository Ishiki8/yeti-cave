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
    $lot = getLotById($con);
    $maxBetSum = getMaxBetSumForLot($con);

    if ($maxBetSum === 0) {
        $minBet = $lot['start_price'] + $lot['step'];
        $currentPrice = $lot['start_price'];
    } else {
        $minBet = $maxBetSum + $lot['step'];
        $currentPrice = $maxBetSum;
    }

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $bet = getPostVal('cost');

        if (empty($bet)) {
            $errors['cost'] = 'Введите вашу ставку';
        }
        if (empty($errors)) {
            $errors = validateLotInt($errors, 'cost');
        }
        if (empty($errors) && intval($bet) < $minBet) {
            $errors['cost'] = 'Ставка меньше минимальной';
        }

        $errors = array_filter($errors);
        if (!$errors) {
            addBet($con);
            header('Location: ../lot.php?id='.$lot['id']);
        }
    }

    $bets = getBetsForLot($con);

    $lot_content = include_template('lot.php', [
        'lot' => $lot,
        'header' => $header,
        'footer' => $footer,
        'bets' => $bets,
        'currentPrice' => $currentPrice,
        'minBet' => $minBet,
        'errors' => $errors,
    ]);
}

print($lot_content);
