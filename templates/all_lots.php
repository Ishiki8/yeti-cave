<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Все лоты</title>
  <link href="../css/normalize.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<div class="page-wrapper">
  <?=$header?>

    <div class="container">
      <section class="lots">
        <h2>Все лоты в категории <span>«<?=getCategoryNameByQueryParameter($con) ?>»</span></h2>
          <?php if(!$lots): ?>
              <h2>Ничего не найдено по вашему запросу</h2>
          <?php else: ?>
              <?= $lotsListContent ?>
          <?php endif ?>
      </section>
        <?= $paginationContent ?>
    </div>

  </main>
</div>

<?=$footer?>

</body>
</html>
