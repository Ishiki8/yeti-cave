<header class="main-header">
        <div class="main-header__container container">
            <h1 class="visually-hidden">YetiCave</h1>
            <a class="main-header__logo" href="../index.php">
                <img src="../img/logo.svg" width="160" height="39" alt="Логотип компании YetiCave">
            </a>
            <form class="main-header__search" method="get" action="../search.php" autocomplete="off">
                <input type="search" name="search" value="<?= getQueryParameter('search')?>" placeholder="Поиск лота">
                <input class="main-header__search-btn" type="submit" name="find" value="Найти">
            </form>
            <?php if(isset($_SESSION['username'])): ?>
                <a class="main-header__add-lot button" href="../add.php">Добавить лот</a>
                <nav class="user-menu">
                    <div class="user-menu__logged">
                        <p><?=htmlspecialchars($_SESSION['username'])?></p>
                        <a class="user-menu__bets" href="../my_bets.php">Мои ставки</a>
                        <a class="user-menu__logout" href="../logout.php">Выход</a>
                    </div>

                <?php else: ?>
                    <ul class="user-menu__list">
                        <li class="user-menu__item">
                            <a href="../registration.php">Регистрация</a>
                        </li>
                        <li class="user-menu__item">
                            <a href="../login.php">Вход</a>
                        </li>
                    </ul>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <?php if(isset($categories)): ?>
    <main>
        <nav class="nav">
            <ul class="nav__list container">
                <?php foreach($categories as $item):?>
                <li class="nav__item <?php if ($item['code'] === getQueryParameter('category')):?>nav__item--current<?php endif; ?>">
                    <a href="../all_lots.php<?="?category="."{$item['code']}"?>"><?=htmlspecialchars($item['title'])?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    <?php endif; ?>