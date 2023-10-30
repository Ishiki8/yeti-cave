<?php
require_once('init.php');
require_once('helpers.php');
require_once('functions.php');

CONST LOTS_PER_PAGE = 3;

$currentPage = 1;
$searchQueryParameter =  htmlspecialchars(getQueryParameter('search'));
isset($_GET['page']) ? $currentPage = htmlspecialchars($_GET['page']) : $_GET['page'] = 1;

$foundLots = findLotsBySearchQuery($con, $searchQueryParameter);
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
    $search_content = include_template('search.php', [
        'header' => $header,
        'footer' => $footer,
        'lotsForPagination' => getLotsForPagination(
            $con,
            $searchQueryParameter,
            LOTS_PER_PAGE,
        ),
        'searchQueryParameter' => $searchQueryParameter,
        'totalPages' => $totalPages,
        'currentPage' => $currentPage,
    ]);
}

print($search_content);