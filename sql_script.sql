CREATE DATABASE `db-group4`;
USE `db-group4`;

CREATE TABLE `user` (
    `user_id` INT PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(30) NOT NULL,
    `password` VARCHAR(30), 
    `email` VARCHAR(30) NOT NULL, 
    `accountType` VARCHAR(10) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE `auctions` (
    `item_id` INT PRIMARY KEY AUTO_INCREMENT,
    `title` VARCHAR(30) NOT NULL,
    `details` VARCHAR(200), 
    `category` VARCHAR(20) NOT NULL, 
    `startPrice` INT NOT NULL,
    `reservePrice` INT, 
    `endDate` DATETIME NOT NULL,
    `seller_id` INT,
    FOREIGN KEY (seller_id) REFERENCES user(user_id)
) ENGINE = InnoDB;

CREATE TABLE `watchlist` (
    `list_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `item_id` INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id),
    FOREIGN KEY (item_id) REFERENCES auctions(item_id)
 ) ENGINE = InnoDB;

CREATE TABLE `bids` (
    `bid_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL, 
    `item_id` INT NOT NULL,
    `bidPrice` VARCHAR(30) NOT NULL,
    `bidTime` DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id),
    FOREIGN KEY (item_id) REFERENCES auctions(item_id)
 ) ENGINE = InnoDB;

-- *****注意
-- 文件中使用的数据库名叫db-group4， 请在测试前自行修改文件或database scheme
-- (没有预先创建过直接用我的也行 CREATE DATABASE `db-group4`;)
-- 本表格为临时满足php参数要求的未完成版，可能会在之后更新其他属性
-- （例如buyer，seller等其他尚未讨论的接口, auction的成交状态etc）
-- 如有出现报错请及时反馈哦
