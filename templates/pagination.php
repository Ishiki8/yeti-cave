<?php if($totalPages > 1): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
            <a href="<?= getUrlForPagination($_GET['page'] - 1) ?>">
                Назад
            </a>
        </li>
        <?php if($currentPage == 1): ?>
            <style>
                .pagination-item-prev {
                    visibility: hidden;
                }
                .pagination-item-next {
                    visibility: visible;
                }
            </style>
        <?php endif; ?>

        <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <li class="pagination-item <?= $currentPage == $i ? "pagination-item-active" : ""?>">
                <a href="<?= getUrlForPagination($i) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <li class="pagination-item pagination-item-next">
            <a href="<?= getUrlForPagination($_GET['page'] + 1) ?>">
                Вперед
            </a>
        </li>
        <?php if($currentPage >= $totalPages): ?>
            <style>
                .pagination-item-next {
                    visibility: hidden;
                }
                .pagination-item-prev {
                    visibility: visible;
                }
            </style>
        <?php endif; ?>
    </ul>
<?php endif ?>