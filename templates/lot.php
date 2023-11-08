<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?=$lot['title']?></title>
    <link href="../css/normalize.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<div class="page-wrapper">
    <?=$header?>
        <section class="lot-item container">
            <h2>
                <?=htmlspecialchars($lot['title']);?>
            </h2>
            <div class="lot-item__content">
                <div class="lot-item__left">
                    <div class="lot-item__image">
                        <img src="<?=htmlspecialchars($lot['image']);?>" width="730" height="548" alt="<?=htmlspecialchars($lot['category_code'])?>">
                    </div>
                    <p class="lot-item__category">Категория: <span><?=htmlspecialchars($lot['category_name']);?></span></p>
                    <p class="lot-item__description"><?=htmlspecialchars($lot['description']);?></p>
                </div>
                <div class="lot-item__right">
                    <?php if(isset($_SESSION['username'])): ?>
                        <div class="lot-item__state">
                            <?php
                                $array = leftTimeToDate($lot['end_date']);
                                $hours = $array[0];
                                $mins = $array[1]
                            ?>
                            <div class="lot-item__timer timer <?php if(intval($hours) < 24): ?>timer--finishing<?php endif; ?>">
                                <?="{$hours}:{$mins}"?>
                            </div>
                            <div class="lot-item__cost-state">
                                <div class="lot-item__rate">
                                    <span class="lot-item__amount">Текущая цена</span>
                                    <span class="lot-item__cost"><?=convertNumberToPrice(htmlspecialchars($lot['start_price']));?></span>
                                </div>
                                <div class="lot-item__min-cost">
                                    Мин. ставка <span><?=convertNumberToPrice(htmlspecialchars($lot['start_price'] + $lot['step']));?></span>
                                </div>
                            </div>
    <!--                        <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">-->
    <!--                            <p class="lot-item__form-item form__item form__item--invalid">-->
    <!--                                <label for="cost">Ваша ставка</label>-->
    <!--                                <input id="cost" type="text" name="cost" placeholder="--><?php //=htmlspecialchars($lot['start_price'] + $lot['step']);?><!--">-->
    <!--                                <span class="form__error">Введите наименование лота</span>-->
    <!--                            </p>-->
    <!--                            <button type="submit" class="button">Сделать ставку</button>-->
    <!--                        </form>-->
                        </div>
                    <?php endif; ?>
                </div>
        </section>
    </main>
</div>

<?=$footer?>

</body>
</html>