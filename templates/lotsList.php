<ul class="lots__list">
    <?php foreach ($lots as $item): ?>
        <li class="lots__item lot">
            <div class="lot__image">
                <img src="<?=htmlspecialchars($item['image']);?>" width="350" height="260" alt="<?=htmlspecialchars($item['category_code']);?>">
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
                    ?>
                    <div class="lot__timer timer <?php if(intval($hours) < 24): ?>timer--finishing<?php endif; ?>">
                        <?="{$hours}:{$mins}"?>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach;?>
</ul>
