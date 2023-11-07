<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Результаты поиска</title>
  <link href="../css/normalize.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<div class="page-wrapper">

  <?= $header ?>
    <div class="container">
      <section class="lots">
        <?php if(!$lotsForPagination): ?>
        <h2>Ничего не найдено по вашему запросу</h2>
        <?php else: ?>
        <h2>Результаты поиска по запросу «<span><?= $searchQueryParameter ?></span>»</h2>
        <ul class="lots__list">
          <?php foreach ($lotsForPagination as $item): ?>
              <li class="lots__item lot">
                  <div class="lot__image">
                      <img src="<?=htmlspecialchars($item['image']);?>" width="350" height="260" alt="">
                  </div>
                  <div class="lot__info">
                      <span class="lot__category"><?=htmlspecialchars($item['category_name']);?></span>
                      <h3 class="lot__title"><a class="text-link" href="lot.php<?="?id="."{$item['id']}"?>"><?=htmlspecialchars($item['title']);?></a></h3>
                      <div class="lot__state">
                          <div class="lot__rate">
                              <span class="lot__amount">Стартовая цена</span>
                              <span class="lot__cost"><?=convertNumberToPrice(htmlspecialchars($item['start_price']));?></span>
                          </div>
                          <?php
                          $array = leftTimeToDate($item['end_date']);
                          $hours = $array[0];
                          $mins = $array[1];
                          $seconds = $array[2];
                          ?>
                          <div class="lot__timer timer <?php if(intval($hours) < 24): ?>timer--finishing<?php endif; ?>">
                              <?="{$hours}:{$mins}:{$seconds}"?>
                          </div>
                      </div>
                  </div>
              </li>
          <?php endforeach;?>
        </ul>
        <?php endif ?>
      </section>
        <?php if($lotsForPagination): ?>
        <ul class="pagination-list">
            <?php if($currentPage != 1): ?>
                <li class="pagination-item pagination-item-prev">
                    <a href="search.php?search=<?= $searchQueryParameter ?>&page=<?= $_GET['page'] - 1 ?>">
                        Назад
                    </a>
                </li>
            <?php else: ?>
                <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
            <?php endif; ?>

            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                <li class="pagination-item <?= $currentPage == $i ? "pagination-item-active" : ""?>">
                    <a href="search.php?search=<?= $searchQueryParameter ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if($currentPage < $totalPages): ?>
                <li class="pagination-item pagination-item-next">
                    <a href="search.php?search=<?= $searchQueryParameter ?>&page=<?= $_GET["page"] + 1 ?>">
                        Вперед
                    </a>
                </li>
            <?php else: ?>
                <li class="pagination-item pagination-item-next"><a>Вперед</a></li>
            <?php endif; ?>
        </ul>
        <?php endif ?>
    </div>
  </main>
</div>

<?= $footer ?>

</body>
</html>
