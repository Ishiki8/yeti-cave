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
                    <?php if(isset($_SESSION['username'])):?>
                        <div class="lot-item__state">
                            <?php
                                $array = leftTimeToDate($lot['end_date']);
                                $hours = $array[0];
                                $mins = $array[1];

                                $isLotEnd = intval($hours) < 0;

                                if($isLotEnd):
                            ?>
                            <div class="timer timer--end">
                                Торги окончены
                            </div>
                            <?php else:?>
                            <div class="lot-item__timer timer">
                                <?="{$hours}:{$mins}"?>
                            </div>
                            <div class="lot-item__cost-state">
                                <div class="lot-item__rate">
                                    <span class="lot-item__amount">Текущая цена</span>
                                    <span class="lot-item__cost"><?=convertNumberToPrice($currentPrice);?></span>
                                </div>
                                <div class="lot-item__min-cost">
                                    Мин. ставка <span><?=convertNumberToPrice($minBet);?></span>
                                </div>
                            </div>
                            <?php if(intval($_SESSION['user_id']) !== intval($lot['author_id'])
                                        && intval($_SESSION['user_id']) !== intval($lot['lastBetUserId'])):?>
                            <form class="lot-item__form" action="../lot.php?id=<?=$lot['id']?>" method="post" autocomplete="off">
                                <p class="lot-item__form-item form__item <?php if(isset($errors['cost'])):?>form__item--invalid<?php endif;?>">
                                    <label for="cost">Ваша ставка</label>
                                    <input id="cost" type="text" name="cost" placeholder="<?=$minBet?>" value="<?= getPostVal('cost')?>">
                                    <span class="form__error"><?= $errors['cost'] ?? "";?></span>
                                </p>
                                <button type="submit" class="button">Сделать ставку</button>
                            </form>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php $betsCount = count($bets);
                          if($betsCount !== 0):?>
                        <div class="history">
                            <h3>История ставок (<span><?=count($bets)?></span>)</h3>
                            <table class="history__list">
                                <?php foreach($bets as $item): ?>
                                    <tr class="history__item">
                                        <td class="history__name"><?=$item['username']?></td>
                                        <td class="history__price"><?=convertNumberToPrice(intval($item['sum']))?></td>
                                        <td class="history__time"><?=formatDate($item['date'])?> назад</td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
        </section>
    </main>
</div>

<?=$footer?>

</body>
</html>