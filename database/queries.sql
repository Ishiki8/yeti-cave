INSERT INTO `Category`(`title`, `code`)
VALUES
('Доски и лыжи', 'boards'),
('Крепления', 'attachment'),
('Ботинки', 'boots'),
('Одежда', 'clothing'),
('Инструменты', 'tools'),
('Разное', 'other');

INSERT INTO `User`(`email`, `name`, `password`, `contacts`)
VALUES
    ('bebrik2004@gmail.com', 'Виктор', 'root228', '88005553535'),
    ('aboba2003@mail.ru', 'Степан', 'koren1337', 'https://vk.com/id2353446'),
    ('maskimk0073@mail.ru', 'Маским', 'libreoffice', '84345342423'),
    ('grandmaster@mail.ru', 'Абдулла', 'microlove1s', 'https://vk.com/id546534');

INSERT INTO `Lot`(`title`, `description`, `image`, `start_price`, `end_date`,
                  `step`, `author_id`, `winner_id`, `category_id`)
VALUES
    ('2014 Rossignol District Snowboard', 'Лорем ипсум че то там', 'img/lot-1.jpg',
     10999, '2023-09-14', 500, 1, 3, 1),

    ('DC Ply Mens 2016/2017 Snowboard', 'Лорем ипсум мега 2', 'img/lot-2.jpg',
     159999, '2023-10-12', 120, 2, null, 1),

    ('Крепления Union Contact Pro 2015 года размер L/XL', 'Лорем ипсум ультра 3', 'img/lot-3.jpg',
     8000, '2023-10-10', 230, 3, null, 2),

    ('Ботинки для сноуборда DC Mutiny Charocal', 'Лорем ипсум супер 4', 'img/lot-4.jpg',
     10999, '2023-10-01', 1000, 4, null, 3),

    ('Куртка для сноуборда DC Mutiny Charocal', 'Лорем ипсум омега 5', 'img/lot-5.jpg',
     7500, '2023-09-28', 10000, 2, null, 4),

    ('Маска Oakley Canopy', 'Лорем ипсум че то там', 'img/lot-6.jpg',
     5400, '2023-09-27', 20, 1, null, 6);

INSERT INTO `Bet`(`sum`, `user_id`, `lot_id`)
VALUES
    (1200, 3, 2),
    (10000, 4, 5),
    (20000, 3, 5);


-- получить список всех категорий;
SELECT * FROM `Category`;

-- получить cписок лотов, которые еще не истекли отсортированных по дате публикации, от новых к старым.
-- Каждый лот должен включать название, стартовую цену, ссылку на изображение, название категории и дату окончания торгов;
SELECT `L`.`id`, `L`.`title`, `start_price`, `image`, `C`.`title` AS `category_name`, `create_date`, `end_date`
FROM `Lot` AS `L`
INNER JOIN `Category` AS `C` ON `L`.`category_id` = `C`.`id`
WHERE `end_date` >= CURRENT_DATE
ORDER BY `create_date` DESC;

-- показать информацию о лоте по его ID. Вместо id категории должно выводиться  название категории,
-- к которой принадлежит лот из таблицы категорий;
SELECT `Lot`.*, `C`.`title` AS `category_name`,`C`.`code` AS `category_code`
FROM `Lot`
INNER JOIN `Category` AS `C` ON `Lot`.`category_id` = `C`.`id`
WHERE `Lot`.`id` = 2;

-- обновить название лота по его идентификатору;
UPDATE `Lot`
SET `title` = 'Маска Coloss Wanaby'
WHERE `id` = 6;

-- получить список ставок для лота по его идентификатору с сортировкой по дате.
-- Список должен содержать дату и время размещения ставки, цену, по которой пользователь
-- готов приобрести лот, название лота и имя пользователя, сделавшего ставку
SELECT `date`, `sum`, `L`.`title` AS `lot_name`, `U`.`name` AS `user_name`
FROM `Bet` AS `B`
INNER JOIN `Lot` AS `L` ON `B`.`lot_id` = `L`.`id`
INNER JOIN `User` AS `U` ON `B`.`user_id` = `U`.`id`
WHERE `L`.`id` = 5
ORDER BY `date` DESC;