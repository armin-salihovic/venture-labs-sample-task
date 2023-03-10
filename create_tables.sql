CREATE TABLE `user`
(
    `userid`     INT         NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username`   VARCHAR(45) NOT NULL,
    `password`   VARCHAR(45) NOT NULL,
    `permission` VARCHAR(45) NOT NULL,
    `readonly`   VARCHAR(45) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE `blog`
(
    `id`     INT  NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `text`   TEXT NOT NULL,
    `userid` INT  NOT NULL
) ENGINE = InnoDB;