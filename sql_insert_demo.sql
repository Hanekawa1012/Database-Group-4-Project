CREATE DATABASE `db-group4`;

USE `db-group4`;

CREATE TABLE `user` (
    `user_id` INT PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `accountType` ENUM('buyer', 'seller') NOT NULL
) ENGINE = InnoDB;

CREATE TABLE `buyer` (
    `user_id` INT PRIMARY KEY,
    FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `seller` (
    `user_id` INT PRIMARY KEY,
    FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `auctions` (
    `item_id` INT PRIMARY KEY AUTO_INCREMENT,
    `title` VARCHAR(50) NOT NULL,
    `details` TEXT,
    `category` VARCHAR(30) NOT NULL,
    `startPrice` DECIMAL(10, 2) NOT NULL,
    `reservePrice` DECIMAL(10, 2),
    `endDate` DATETIME NOT NULL,
    `seller_id` INT NOT NULL,
    `status` ENUM('open', 'sold', 'unsold', 'cancelled') DEFAULT 'open',
    FOREIGN KEY (`seller_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `watchlist` (
    `list_id` INT PRIMARY KEY AUTO_INCREMENT,
    `buyer_id` INT NOT NULL,
    `item_id` INT NOT NULL,
    FOREIGN KEY (`buyer_id`) REFERENCES `buyer` (`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`item_id`) REFERENCES `auctions` (`item_id`) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `bids` (
    `bid_id` INT PRIMARY KEY AUTO_INCREMENT,
    `buyer_id` INT NOT NULL,
    `item_id` INT NOT NULL,
    `bidPrice` DECIMAL(10, 2) NOT NULL,
    `bidTime` DATETIME NOT NULL,
    FOREIGN KEY (`buyer_id`) REFERENCES `buyer` (`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`item_id`) REFERENCES `auctions` (`item_id`) ON DELETE CASCADE
) ENGINE = InnoDB;

INSERT INTO
    `user` (username, password, email, accountType)
VALUES
    (
        'seller1',
        'password1',
        'seller1@example.com',
        'seller'
    ),
    (
        'seller2',
        'password2',
        'seller2@example.com',
        'seller'
    ),
    (
        'seller3',
        'password3',
        'seller3@example.com',
        'seller'
    ),
    (
        'buyer1',
        'password4',
        'buyer1@example.com',
        'buyer'
    ),
    (
        'buyer2',
        'password5',
        'buyer2@example.com',
        'buyer'
    ),
    (
        'buyer3',
        'password6',
        'buyer3@example.com',
        'buyer'
    );

INSERT INTO
    `seller` (user_id)
SELECT
    user_id
FROM
    `user`
WHERE
    accountType = 'seller';

INSERT INTO
    `buyer` (user_id)
SELECT
    user_id
FROM
    `user`
WHERE
    accountType = 'buyer';

INSERT INTO
    `auctions` (
        title,
        details,
        category,
        startPrice,
        reservePrice,
        endDate,
        seller_id
    )
VALUES
    (
        'Vintage Vase',
        'Antique vase from 19th century',
        'Antiques',
        100.00,
        200.00,
        '2024-12-01 18:00:00',
        1
    ),
    (
        'Gaming Laptop',
        'High-performance gaming laptop',
        'Electronics',
        800.00,
        1000.00,
        '2024-11-30 20:00:00',
        1
    ),
    (
        'Mountain Bike',
        'Brand new mountain bike',
        'Sports',
        300.00,
        400.00,
        '2024-11-25 15:00:00',
        2
    ),
    (
        'Smartphone',
        'Latest model smartphone',
        'Electronics',
        600.00,
        700.00,
        '2024-11-28 22:00:00',
        2
    ),
    (
        'Art Painting',
        'Abstract art painting',
        'Art',
        150.00,
        250.00,
        '2024-12-03 10:00:00',
        3
    ),
    (
        'Luxury Watch',
        'Swiss luxury watch',
        'Accessories',
        2000.00,
        2500.00,
        '2024-12-05 21:00:00',
        3
    ),
    (
        'Book Collection',
        'Rare book collection',
        'Books',
        50.00,
        80.00,
        '2024-11-29 17:00:00',
        1
    ),
    (
        'Table Lamp',
        'Handmade wooden table lamp',
        'Furniture',
        40.00,
        NULL,
        '2024-12-02 19:00:00',
        2
    ),
    (
        'Camera Lens',
        'Wide-angle camera lens',
        'Photography',
        300.00,
        400.00,
        '2024-11-27 16:00:00',
        3
    ),
    (
        'Guitar',
        'Electric guitar with amp',
        'Music',
        500.00,
        600.00,
        '2024-12-06 23:00:00',
        2
    ),
    (
        'Leather Jacket',
        'Genuine leather jacket, size M',
        'Fashion',
        80.00,
        150.00,
        '2024-11-26 18:00:00',
        1
    ),
    (
        'Drone',
        'Quadcopter drone with HD camera',
        'Electronics',
        200.00,
        300.00,
        '2024-11-28 20:00:00',
        2
    ),
    (
        'Coffee Machine',
        'Espresso coffee machine',
        'Appliances',
        100.00,
        NULL,
        '2024-12-01 10:00:00',
        3
    ),
    (
        'Yoga Mat',
        'Eco-friendly yoga mat',
        'Fitness',
        25.00,
        NULL,
        '2024-11-27 09:00:00',
        1
    ),
    (
        'Camping Tent',
        '4-person waterproof camping tent',
        'Outdoors',
        150.00,
        200.00,
        '2024-11-30 19:00:00',
        2
    ),
    (
        'Rare Coin',
        'Gold coin from 18th century',
        'Collectibles',
        500.00,
        700.00,
        '2024-12-03 14:00:00',
        3
    ),
    (
        'Bluetooth Speaker',
        'Portable Bluetooth speaker',
        'Electronics',
        50.00,
        80.00,
        '2024-11-25 12:00:00',
        2
    ),
    (
        'Skateboard',
        'Custom-designed skateboard',
        'Sports',
        60.00,
        NULL,
        '2024-11-29 16:00:00',
        3
    ),
    (
        'Cooking Set',
        'Stainless steel pots and pans',
        'Kitchen',
        120.00,
        180.00,
        '2024-11-26 11:00:00',
        1
    ),
    (
        'Vinyl Records',
        'Classic rock album collection',
        'Music',
        200.00,
        300.00,
        '2024-12-04 20:00:00',
        3
    );

INSERT INTO
    `bids` (buyer_id, item_id, bidPrice, bidTime)
VALUES
    (4, 1, 120.00, '2024-11-18 10:00:00'),
    (5, 1, 150.00, '2024-11-18 12:00:00'),
    (6, 2, 850.00, '2024-11-18 13:00:00'),
    (5, 3, 350.00, '2024-11-18 14:00:00'),
    (4, 4, 620.00, '2024-11-18 15:00:00'),
    (6, 5, 180.00, '2024-11-18 16:00:00'),
    (4, 6, 2100.00, '2024-11-18 17:00:00'),
    (5, 7, 55.00, '2024-11-18 18:00:00'),
    (6, 8, 50.00, '2024-11-18 19:00:00'),
    (4, 9, 320.00, '2024-11-18 20:00:00'),
    (4, 11, 90.00, '2024-11-18 10:30:00'),
    (5, 11, 100.00, '2024-11-18 11:00:00'),
    (6, 12, 220.00, '2024-11-18 12:00:00'),
    (5, 13, 110.00, '2024-11-18 12:30:00'),
    (4, 14, 30.00, '2024-11-18 13:00:00'),
    (6, 15, 180.00, '2024-11-18 13:30:00'),
    (4, 16, 510.00, '2024-11-18 14:00:00'),
    (5, 17, 55.00, '2024-11-18 14:30:00'),
    (6, 18, 60.00, '2024-11-18 15:00:00'),
    (4, 19, 125.00, '2024-11-18 15:30:00');