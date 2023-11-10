<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои ставки</title>
    <link href="../css/normalize.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<div class="page-wrapper">
    <?= $header ?>
        <section class="rates container">
            <h2>Мои ставки</h2>
            <?php if(empty($userBets)):?>
            <h2>Вы еще не сделали ни одной ставки</h2>
            <?php else:?>
            <table class="rates__list">
                <?php foreach($userBets as $bet): ?>
                <?php
                $array = leftTimeToDate($bet['lot_end_date']);
                $hours = $array[0];
                $mins = $array[1];

                $itemClass = 'rates__item';
                $timerClass = 'timer';
                $isWinner = intval($_SESSION['user_id']) === $bet['lot_winner_id'];
                $timerText = "{$hours}:{$mins}";

                if(intval($hours) < 0) {
                    if($isWinner && getLastUserBetIdForLot($con, $bet['lot_id']) === $bet['id']) {
                        $itemClass .= ' rates__item--win';
                        $timerClass .= ' timer--win';
                        $timerText = 'Ставка выиграла';
                    } else {
                        $itemClass .= ' rates__item--end';
                        $timerClass .= ' timer--end';
                        $timerText = 'Торги окончены';
                    }

                } else if(intval($hours) < 24) {
                    $timerClass .= ' timer--finishing';
                }
                ?>
                <tr class="<?= $itemClass ?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="<?=$bet['lot_img']?>" width="54" height="40" alt="<?=$bet['lot_category_code']?>">
                        </div>
                        <?php if($isWinner): ?>
                        <div>
                            <h3 class="rates__title"><a href="../lot.php?id=<?=$bet['lot_id']?>"><?=$bet['lot_title']?></a></h3>
                            <p><?=getUserContacts($con, $bet['lot_author_id'])?></p>
                        </div>
                        <?php else: ?>
                        <h3 class="rates__title"><a href="../lot.php?id=<?=$bet['lot_id']?>"><?=$bet['lot_title']?></a></h3>
                        <?php endif; ?>
                    </td>
                    <td class="rates__category">
                        <?=$bet['lot_category_title']?>
                    </td>
                    <td class="rates__timer">
                        <div class="<?= $timerClass ?>">
                            <?=$timerText?>
                        </div>
                    </td>
                    <td class="rates__price">
                        <?=convertNumberToPrice($bet['sum'])?>
                    </td>
                    <td class="rates__time">
                        <?=formatDate($bet['date'])?>
                    </td>
                </tr>
                <?php endforeach;?>
            </table>
            <?php endif; ?>
        </section>
    </main>

</div>
<?= $footer ?>
</body>
</html>

