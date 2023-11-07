<?php
require_once('init.php');
require_once('helpers.php');
require_once('functions.php');

CONST LOTS_PER_PAGE = 3;

$currentPage = 1;
$searchQueryParameter =  htmlspecialchars(getQueryParameter('search'));
isset($_GET['page']) ? $currentPage = htmlspecialchars($_GET['page']) : $_GET['page'] = 1;

if ($searchQueryParameter) {
    $foundLots = findLotsBySearchQuery($con, $searchQueryParameter);
} else {
    $foundLots = getLotsList($con);
}

$totalLots = count($foundLots);
$totalPages = ceil($totalLots / LOTS_PER_PAGE);

$header = include_template('header.php',[
    'categories' => getCategories($con),
    'is_auth' => $is_auth,
    'user_name' => $user_name,
]);

$footer = include_template('footer.php', [
    'categories' => getCategories($con),
]);

if(($_GET['page'] < 1 || $_GET['page'] > $totalPages) && $searchQueryParameter && $foundLots != null) {
    http_response_code(404);

    $search_content = include_template('404.php',[
        'title' => 'Страница не найдена',
        'header' => $header,
        'footer' => $footer,
    ]);
} else {
    if ($searchQueryParameter) {
        $lotsForPagination = getlotsForPagination($con, LOTS_PER_PAGE, $searchQueryParameter);
    } else {
        $lotsForPagination = getlotsForPagination($con, LOTS_PER_PAGE);
    }

    $search_content = include_template('search.php', [
        'header' => $header,
        'footer' => $footer,
        'lotsForPagination' => $lotsForPagination,
        'searchQueryParameter' => $searchQueryParameter,
        'totalPages' => $totalPages,
        'currentPage' => $currentPage,
    ]);
}

print($search_content);