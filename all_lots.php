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

CONST LOTS_PER_PAGE = 3;

$currentPage = 1;
$queryParameterValue =  getQueryParameter('category');

if (!$queryParameterValue) {
    header('Location: /index.php');
    exit;
}

$data = $_GET;

isset($data['page']) ? $currentPage = htmlspecialchars($data['page']) : $data['page'] = 1;

if ($data['page'] === 1) {
    $scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
    $query = http_build_query($data);
    $url = "/" . $scriptname . "?" . $query;
    header("Location: ". $url);
    exit;
}

$totalLots = countLotsByQueryParameter($con, 'category');
$totalPages = ceil($totalLots / LOTS_PER_PAGE);
$totalPages = $totalPages > 0 ? $totalPages : 1;

if($_GET['page'] < 1 || $_GET['page'] > $totalPages) {
    http_response_code(404);

    $all_lots_content = include_template('404.php',[
        'title' => 'Страница не найдена',
        'header' => $header,
        'footer' => $footer,
    ]);
} else {
    $lots = getLotsByCategoryCode($con, LOTS_PER_PAGE, $queryParameterValue);

    $paginationContent = include_template('pagination.php', [
        'totalPages' => $totalPages,
        'currentPage' => $currentPage,
    ]);

    $lotsListContent = include_template('lotsList.php', [
        'lots' => $lots,
    ]);

    $all_lots_content = include_template('all_lots.php', [
        'con' => $con,
        'header' => $header,
        'footer' => $footer,
        'lots' => $lots,
        'queryParameterValue' => $queryParameterValue,
        'lotsListContent' => $lotsListContent,
        'paginationContent' => $paginationContent,
    ]);
}

print($all_lots_content);