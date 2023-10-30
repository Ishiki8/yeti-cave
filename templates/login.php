<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Вход</title>
  <link href="../css/normalize.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<div class="page-wrapper">

    <?=$header?>

    <form class="form container <?php $errors ? "form--invalid" : "";?>" action="../login.php" method="post">
      <h2>Вход</h2>
      <div class="form__item <?=isset($errors['email']) ? "form__item--invalid" : "";?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=getPostVal('email');?>">
        <span class="form__error"><?=$errors['email'] ?? "";?></span>
      </div>
      <div class="form__item form__item--last <?=isset($errors['password']) ? "form__item--invalid" : "";?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?=$errors['password'] ?? "";?></span>
      </div>
      <button type="submit" class="button">Войти</button>
    </form>
  </main>

</div>

<?=$footer?>

</body>
</html>
