CREATE TABLE `Category`
(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `title` VARCHAR(25) UNIQUE NOT NULL,
    `code` VARCHAR(15) UNIQUE NOT NULL
);

CREATE TABLE `User`
(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `reg_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `email` VARCHAR(50) UNIQUE NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `contacts` VARCHAR(100) NOT NULL
);

CREATE TABLE `Lot`
(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `create_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `title` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `image` VARCHAR(50) NOT NULL,
    `start_price` INT NOT NULL,
    `end_date` DATE NOT NULL,
    `step` INT NOT NULL,
    `author_id` INT NOT NULL,
    `winner_id` INT,
    `category_id` INT NOT NULL,

     FOREIGN KEY (`author_id`) REFERENCES `User`(`id`) ON DELETE CASCADE,
     FOREIGN KEY (`winner_id`) REFERENCES  `User`(`id`)ON DELETE CASCADE,
     FOREIGN KEY (`category_id`) REFERENCES `Category`(`id`) ON DELETE CASCADE
);

CREATE TABLE `Bet`
(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `sum` INT NOT NULL,
    `user_id` INT NOT NULL,
    `lot_id` INT NOT NULL,

     FOREIGN KEY (`user_id`) REFERENCES `User`(`id`) ON DELETE CASCADE,
     FOREIGN KEY (`lot_id`) REFERENCES `Lot`(`id`) ON DELETE CASCADE
);

ALTER TABLE `Lot`
ADD FULLTEXT INDEX lot_ft_search(title, description);