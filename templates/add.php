<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Добавление лота</title>
  <link href="../css/normalize.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  <link href="../css/flatpickr.min.css" rel="stylesheet">
</head>
<body>

<div class="page-wrapper">

    <?=$header?>

    <form class="form form--add-lot container <?php if(!empty($errors)):?>form--invalid<?php endif;?>" action="../add.php" method="post" enctype="multipart/form-data">
      <h2>Добавление лота</h2>
      <div class="form__container-two">
        <div class="form__item <?php if(isset($errors['lot-name'])):?>form__item--invalid<?php endif;?>">
          <label for="lot-name">Наименование <sup>*</sup></label>
          <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?=getPostVal('lot-name');?>">
          
          <span class="form__error"><?=$errors['lot-name'] ?? "";?></span>
          
        </div>
        <div class="form__item <?php if(isset($errors['category'])):?>form__item--invalid<?php endif;?>">
          <label for="category">Категория <sup>*</sup></label>
          <select id="category" name="category">
            <option value="0">Выберите категорию</option>
            <?php foreach ($categories as $item):?>
                <option <?=getPostVal('category') === $item['id'] ? 'selected value='.$item['id']: 'value='.$item['id'];?>><?=$item['title'];?></option>
            <?php endforeach; ?>
          </select>
          <span class="form__error"><?=$errors['category'] ?? "";?></span>
        </div>
      </div>
      <div class="form__item form__item--wide <?php if(isset($errors['message'])):?>form__item--invalid<?php endif;?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите описание лота" ><?=getPostVal('message');?></textarea>
        <span class="form__error"><?=$errors['message'] ?? "";?></span>
      </div>
      <div class="form__item form__item--file <?php if(isset($errors['lot-img'])):?>form__item--invalid<?php endif;?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" id="lot-img" name="lot-img" value="">
          <label for="lot-img">
            Добавить
          </label>
          <span class="form__error"><?=$errors['lot-img'] ?? "";?></span>
        </div>
      </div>
      <div class="form__container-three">
        <div class="form__item form__item--small <?php if(isset($errors['lot-rate'])):?>form__item--invalid<?php endif;?>">
          <label for="lot-rate">Начальная цена <sup>*</sup></label>
          <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?=getPostVal('lot-rate');?>">
          <span class="form__error"><?=$errors['lot-rate'] ?? "";?></span>
        </div>
        <div class="form__item form__item--small <?php if(isset($errors['lot-step'])):?>form__item--invalid<?php endif;?>">
          <label for="lot-step">Шаг ставки <sup>*</sup></label>
          <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?=getPostVal('lot-step');?>">
          <span class="form__error"><?=$errors['lot-step'] ?? "";?></span>
        </div>
        <div class="form__item <?php if(isset($errors['lot-date'])):?>form__item--invalid<?php endif;?>">
          <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
          <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?=getPostVal('lot-date');?>">
          <span class="form__error"><?=$errors['lot-date'] ?? "";?></span>
        </div>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Добавить лот</button>
    </form>
  </main>

</div>

<?=$footer?>

<script src="../flatpickr.js"></script>
<script src="../script.js"></script>
</body>
</html>
