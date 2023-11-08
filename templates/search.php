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
        <?php if(!$lots): ?>
            <h2>Ничего не найдено по вашему запросу</h2>
        <?php else: ?>
            <h2>Результаты поиска по запросу «<span><?= $queryParameterValue ?></span>»</h2>
            <?= $lotsListContent ?>
        <?php endif ?>
      </section>
        <?= $paginationContent ?>
    </div>
  </main>
</div>

<?= $footer ?>

</body>
</html>
