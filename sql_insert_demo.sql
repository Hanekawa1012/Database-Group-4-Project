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

INSERT INTO `user` (username, password, email, accountType) VALUES
    ('seller1', 'password1', 'seller1@example.com', 'seller'),
    ('seller2', 'password2', 'seller2@example.com', 'seller'),
    ('seller3', 'password3', 'seller3@example.com', 'seller'),
    ('buyer1', 'password4', 'buyer1@example.com', 'buyer'),
    ('buyer2', 'password5', 'buyer2@example.com', 'buyer'),
    ('buyer3', 'password6', 'buyer3@example.com', 'buyer');

INSERT INTO `auctions` (title, details, category, startPrice, reservePrice, endDate, seller_id) VALUES
    ('Vintage Vase', 'Antique vase from 19th century', 'Antiques', 100, 200, '2024-12-01 18:00:00', 1),
    ('Gaming Laptop', 'High-performance gaming laptop', 'Electronics', 800, 1000, '2024-11-30 20:00:00', 1),
    ('Mountain Bike', 'Brand new mountain bike', 'Sports', 300, 400, '2024-11-25 15:00:00', 2),
    ('Smartphone', 'Latest model smartphone', 'Electronics', 600, 700, '2024-11-28 22:00:00', 2),
    ('Art Painting', 'Abstract art painting', 'Art', 150, 250, '2024-12-03 10:00:00', 3),
    ('Luxury Watch', 'Swiss luxury watch', 'Accessories', 2000, 2500, '2024-12-05 21:00:00', 3),
    ('Book Collection', 'Rare book collection', 'Books', 50, 80, '2024-11-29 17:00:00', 1),
    ('Table Lamp', 'Handmade wooden table lamp', 'Furniture', 40, NULL, '2024-12-02 19:00:00', 2),
    ('Camera Lens', 'Wide-angle camera lens', 'Photography', 300, 400, '2024-11-27 16:00:00', 3),
    ('Guitar', 'Electric guitar with amp', 'Music', 500, 600, '2024-12-06 23:00:00', 2),
    ('Leather Jacket', 'Genuine leather jacket, size M', 'Fashion', 80, 150, '2024-11-26 18:00:00', 1),
    ('Drone', 'Quadcopter drone with HD camera', 'Electronics', 200, 300, '2024-11-28 20:00:00', 2),
    ('Coffee Machine', 'Espresso coffee machine', 'Appliances', 100, NULL, '2024-12-01 10:00:00', 3),
    ('Yoga Mat', 'Eco-friendly yoga mat', 'Fitness', 25, NULL, '2024-11-27 09:00:00', 1),
    ('Camping Tent', '4-person waterproof camping tent', 'Outdoors', 150, 200, '2024-11-30 19:00:00', 2),
    ('Rare Coin', 'Gold coin from 18th century', 'Collectibles', 500, 700, '2024-12-03 14:00:00', 3),
    ('Bluetooth Speaker', 'Portable Bluetooth speaker', 'Electronics', 50, 80, '2024-11-25 12:00:00', 2),
    ('Skateboard', 'Custom-designed skateboard', 'Sports', 60, NULL, '2024-11-29 16:00:00', 3),
    ('Cooking Set', 'Stainless steel pots and pans', 'Kitchen', 120, 180, '2024-11-26 11:00:00', 1),
    ('Vinyl Records', 'Classic rock album collection', 'Music', 200, 300, '2024-12-04 20:00:00', 3);


INSERT INTO `watchlist` (user_id, item_id) VALUES
    (4, 1), (4, 2), (5, 3), (5, 4), (6, 5),
    (6, 6), (4, 7), (5, 8), (6, 9), (4, 10),
    (4, 11), (4, 12), (5, 13), (5, 14), (6, 15),
    (6, 16), (4, 17), (5, 18), (6, 19), (4, 20),
    (5, 10), (4, 8), (6, 7), (4, 6), (5, 5);


INSERT INTO `bids` (user_id, item_id, bidPrice, bidTime) VALUES
    (4, 1, 120, '2024-11-18 10:00:00'),
    (5, 1, 150, '2024-11-18 12:00:00'),
    (6, 2, 850, '2024-11-18 13:00:00'),
    (5, 3, 350, '2024-11-18 14:00:00'),
    (4, 4, 620, '2024-11-18 15:00:00'),
    (6, 5, 180, '2024-11-18 16:00:00'),
    (4, 6, 2100, '2024-11-18 17:00:00'),
    (5, 7, 55, '2024-11-18 18:00:00'),
    (6, 8, 50, '2024-11-18 19:00:00'),
    (4, 9, 320, '2024-11-18 20:00:00'),
    (4, 11, 90, '2024-11-18 10:30:00'),
    (5, 11, 100, '2024-11-18 11:00:00'),
    (6, 12, 220, '2024-11-18 12:00:00'),
    (5, 13, 110, '2024-11-18 12:30:00'),
    (4, 14, 30, '2024-11-18 13:00:00'),
    (6, 15, 180, '2024-11-18 13:30:00'),
    (4, 16, 510, '2024-11-18 14:00:00'),
    (5, 17, 55, '2024-11-18 14:30:00'),
    (6, 18, 60, '2024-11-18 15:00:00'),
    (4, 19, 330, '2024-11-18 15:30:00'),
    (5, 20, 210, '2024-11-18 16:00:00'),
    (6, 11, 130, '2024-11-18 16:30:00'),
    (5, 6, 2200, '2024-11-18 17:00:00'),
    (4, 7, 65, '2024-11-18 17:30:00'),
    (6, 8, 60, '2024-11-18 18:00:00');