CREATE DATABASE `db-group4`;

USE `db-group4`;

-- User Table
CREATE TABLE `user` (
    `user_id` INT PRIMARY KEY AUTO_INCREMENT,
    `password` VARCHAR(64) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `accountType` ENUM('buyer', 'seller') NOT NULL
) ENGINE = InnoDB;

-- Profile Table
CREATE TABLE `profile` (
    `user_id` INT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL,
    `tel` VARCHAR(15) NOT NULL,
    `address` VARCHAR(100) NOT NULL UNIQUE,
    FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE
) ENGINE = InnoDB;

-- Buyer Table
CREATE TABLE `buyer` (
    `user_id` INT PRIMARY KEY,
    FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE
) ENGINE = InnoDB;

-- Seller Table
CREATE TABLE `seller` (
    `user_id` INT PRIMARY KEY,
    FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE
) ENGINE = InnoDB;

-- Auctions Table
CREATE TABLE `auctions` (
    `item_id` INT PRIMARY KEY AUTO_INCREMENT,
    `title` VARCHAR(50) NOT NULL,
    `details` TEXT,
    `category` VARCHAR(30) NOT NULL,
    `startPrice` DECIMAL(10, 2) NOT NULL,
    `reservePrice` DECIMAL(10, 2) CHECK (
        `reservePrice` IS NULL
        OR `reservePrice` >= `startPrice`
    ),
    `startDate` DATETIME NOT NULL,
    `endDate` DATETIME NOT NULL,
    `seller_id` INT NOT NULL,
    `status` ENUM('active', 'closed', 'cancelled') NOT NULL,
    FOREIGN KEY (`seller_id`) REFERENCES `seller` (`user_id`) ON DELETE CASCADE
) ENGINE = InnoDB;

-- Watchlist Table
CREATE TABLE `watchlist` (
    `list_id` INT PRIMARY KEY AUTO_INCREMENT,
    `buyer_id` INT NOT NULL,
    `item_id` INT NOT NULL,
    UNIQUE (`buyer_id`, `item_id`),
    FOREIGN KEY (`buyer_id`) REFERENCES `buyer` (`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`item_id`) REFERENCES `auctions` (`item_id`) ON DELETE CASCADE
) ENGINE = InnoDB;

-- Bids Table
CREATE TABLE `bids` (
    `bid_id` INT PRIMARY KEY AUTO_INCREMENT,
    `buyer_id` INT NOT NULL,
    `item_id` INT NOT NULL,
    `bidPrice` DECIMAL(10, 2) NOT NULL,
    `bidTime` DATETIME NOT NULL,
    FOREIGN KEY (`buyer_id`) REFERENCES `buyer` (`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`item_id`) REFERENCES `auctions` (`item_id`) ON DELETE CASCADE
) ENGINE = InnoDB;

-- Comments Table
CREATE TABLE `comments` (
    `comment_id` INT PRIMARY KEY AUTO_INCREMENT,
    `item_id` INT NOT NULL,
    `buyer_id` INT NOT NULL,
    `time` DATETIME NOT NULL,
    `content` VARCHAR(1023) NOT NULL,
    `parent_comment_id` INT,
    FOREIGN KEY (`item_id`) REFERENCES `auctions` (`item_id`) ON DELETE CASCADE,
    FOREIGN KEY (`buyer_id`) REFERENCES `buyer` (`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`parent_comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE
) ENGINE = InnoDB;

-- Comment Likes Table
CREATE TABLE `comment_likes` (
    `comment_id` INT NOT NULL,
    `buyer_id` INT NOT NULL,
    PRIMARY KEY (`comment_id`, `buyer_id`),
    FOREIGN KEY (`comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE,
    FOREIGN KEY (`buyer_id`) REFERENCES `buyer` (`user_id`) ON DELETE CASCADE
) ENGINE = InnoDB;

-- Inserting users
INSERT INTO
    `user` (`password`, `email`, `accountType`)
VALUES
    ('password123', 'buyer1@example.com', 'buyer'),
    ('password123', 'buyer2@example.com', 'buyer'),
    ('password123', 'buyer3@example.com', 'buyer'),
    ('password123', 'buyer4@example.com', 'buyer'),
    ('password123', 'buyer5@example.com', 'buyer'),
    ('password123', 'seller1@example.com', 'seller'),
    ('password123', 'seller2@example.com', 'seller'),
    ('password123', 'seller3@example.com', 'seller'),
    ('password123', 'seller4@example.com', 'seller'),
    ('password123', 'seller5@example.com', 'seller');

-- Inserting profiles
INSERT INTO
    `profile` (`user_id`, `username`, `tel`, `address`)
VALUES
    (
        1,
        'BuyerOne',
        '1234567890',
        '123 Buyer St, London'
    ),
    (
        2,
        'BuyerTwo',
        '2345678901',
        '234 Buyer Rd, London'
    ),
    (
        3,
        'BuyerThree',
        '3456789012',
        '345 Buyer Ave, London'
    ),
    (
        4,
        'BuyerFour',
        '4567890123',
        '456 Buyer Blvd, London'
    ),
    (
        5,
        'BuyerFive',
        '5678901234',
        '567 Buyer Ln, London'
    ),
    (
        6,
        'SellerOne',
        '6789012345',
        '678 Seller St, London'
    ),
    (
        7,
        'SellerTwo',
        '7890123456',
        '789 Seller Rd, London'
    ),
    (
        8,
        'SellerThree',
        '8901234567',
        '890 Seller Ave, London'
    ),
    (
        9,
        'SellerFour',
        '9012345678',
        '901 Seller Blvd, London'
    ),
    (
        10,
        'SellerFive',
        '0123456789',
        '012 Seller Ln, London'
    );

-- Inserting buyers
INSERT INTO
    `buyer` (`user_id`)
VALUES
    (1),
    (2),
    (3),
    (4),
    (5);

-- Inserting sellers
INSERT INTO
    `seller` (`user_id`)
VALUES
    (6),
    (7),
    (8),
    (9),
    (10);

-- Inserting auction items
INSERT INTO
    `auctions` (
        `title`,
        `details`,
        `category`,
        `startPrice`,
        `reservePrice`,
        `startDate`,
        `endDate`,
        `seller_id`,
        `status`
    )
VALUES
    (
        'Laptop',
        '14-inch gaming laptop',
        'Electronics',
        500.00,
        750.00,
        '2024-11-01 09:00:00',
        '2024-12-01 09:00:00',
        6,
        'active'
    ),
    (
        'Smartphone',
        'Flagship 5G phone',
        'Electronics',
        300.00,
        500.00,
        '2024-11-01 10:00:00',
        '2024-12-01 10:00:00',
        6,
        'active'
    ),
    (
        'Tablet',
        'High-performance tablet',
        'Electronics',
        200.00,
        350.00,
        '2024-11-02 09:00:00',
        '2024-12-02 09:00:00',
        6,
        'active'
    ),
    (
        'Headphones',
        'Wireless noise-cancelling headphones',
        'Electronics',
        150.00,
        NULL,
        '2024-11-03 09:00:00',
        '2024-12-03 09:00:00',
        7,
        'active'
    ),
    (
        'Camera',
        'DSLR camera',
        'Photography',
        400.00,
        600.00,
        '2024-11-03 10:00:00',
        '2024-12-03 10:00:00',
        7,
        'active'
    ),
    (
        'Desk',
        'Ergonomic office desk',
        'Furniture',
        100.00,
        200.00,
        '2024-11-04 09:00:00',
        '2024-12-04 09:00:00',
        7,
        'active'
    ),
    (
        'Chair',
        'Ergonomic office chair',
        'Furniture',
        50.00,
        100.00,
        '2024-11-05 09:00:00',
        '2024-12-05 09:00:00',
        8,
        'active'
    ),
    (
        'Sofa',
        '3-seater leather sofa',
        'Furniture',
        300.00,
        500.00,
        '2024-11-06 09:00:00',
        '2024-12-06 09:00:00',
        8,
        'active'
    ),
    (
        'Bike',
        'Mountain bike',
        'Sports',
        200.00,
        300.00,
        '2024-11-07 09:00:00',
        '2024-12-07 09:00:00',
        8,
        'active'
    ),
    (
        'Treadmill',
        'Electric treadmill',
        'Fitness',
        400.00,
        600.00,
        '2024-11-08 09:00:00',
        '2024-12-08 09:00:00',
        9,
        'active'
    ),
    (
        'Watch',
        'Smartwatch',
        'Accessories',
        150.00,
        250.00,
        '2024-11-09 09:00:00',
        '2024-12-09 09:00:00',
        9,
        'active'
    ),
    (
        'Necklace',
        'Gold necklace',
        'Jewellery',
        500.00,
        800.00,
        '2024-11-10 09:00:00',
        '2024-12-10 09:00:00',
        9,
        'active'
    ),
    (
        'Ring',
        'Diamond ring',
        'Jewellery',
        1000.00,
        1500.00,
        '2024-11-11 09:00:00',
        '2024-12-11 09:00:00',
        10,
        'active'
    ),
    (
        'Bracelet',
        'Silver bracelet',
        'Jewellery',
        200.00,
        300.00,
        '2024-11-12 09:00:00',
        '2024-12-12 09:00:00',
        10,
        'active'
    ),
    (
        'Shoes',
        'Running shoes',
        'Sportswear',
        100.00,
        NULL,
        '2024-11-13 09:00:00',
        '2024-12-13 09:00:00',
        10,
        'active'
    ),
    (
        'Backpack',
        'Travel backpack',
        'Accessories',
        50.00,
        100.00,
        '2024-11-14 09:00:00',
        '2024-12-14 09:00:00',
        6,
        'active'
    ),
    (
        'Suitcase',
        'Large suitcase',
        'Accessories',
        120.00,
        200.00,
        '2024-11-15 09:00:00',
        '2024-12-15 09:00:00',
        7,
        'active'
    ),
    (
        'TV',
        '4K UHD TV',
        'Electronics',
        600.00,
        900.00,
        '2024-11-16 09:00:00',
        '2024-12-16 09:00:00',
        8,
        'active'
    ),
    (
        'Microwave',
        'Convection microwave oven',
        'Appliances',
        100.00,
        200.00,
        '2024-11-17 09:00:00',
        '2024-12-17 09:00:00',
        9,
        'active'
    ),
    (
        'Refrigerator',
        'Double-door refrigerator',
        'Appliances',
        800.00,
        1200.00,
        '2024-11-18 09:00:00',
        '2024-12-18 09:00:00',
        10,
        'active'
    );

-- Inserting bids
INSERT INTO
    `bids` (`item_id`, `buyer_id`, `bidPrice`, `bidTime`)
VALUES
    (1, 1, 550.00, '2024-11-20 10:00:00'),
    (1, 2, 580.00, '2024-11-20 11:00:00'),
    (2, 3, 320.00, '2024-11-20 12:00:00'),
    (2, 4, 350.00, '2024-11-20 13:00:00'),
    (3, 5, 220.00, '2024-11-21 10:00:00'),
    (3, 1, 250.00, '2024-11-21 11:00:00'),
    (4, 2, 160.00, '2024-11-21 12:00:00'),
    (5, 3, 420.00, '2024-11-21 13:00:00'),
    (5, 4, 450.00, '2024-11-22 10:00:00'),
    (6, 5, 150.00, '2024-11-22 11:00:00'),
    (7, 1, 60.00, '2024-11-22 12:00:00'),
    (8, 2, 310.00, '2024-11-22 13:00:00'),
    (9, 3, 250.00, '2024-11-23 10:00:00'),
    (10, 4, 410.00, '2024-11-23 11:00:00'),
    (11, 5, 170.00, '2024-11-23 12:00:00'),
    (12, 1, 520.00, '2024-11-24 10:00:00'),
    (13, 2, 1020.00, '2024-11-24 11:00:00'),
    (14, 3, 220.00, '2024-11-24 12:00:00'),
    (15, 4, 110.00, '2024-11-25 10:00:00'),
    (16, 5, 60.00, '2024-11-25 11:00:00');

-- Inserting comments
INSERT INTO
    `comments` (`item_id`, `buyer_id`, `content`, `time`)
VALUES
    (
        1,
        1,
        'Is this laptop still in good condition?',
        '2024-11-20 09:30:00'
    ),
    (
        1,
        2,
        'What is the battery life of this laptop?',
        '2024-11-20 10:30:00'
    ),
    (
        2,
        3,
        'Does this smartphone come with accessories?',
        '2024-11-20 11:30:00'
    ),
    (
        3,
        4,
        'Can you provide more photos of the tablet?',
        '2024-11-21 09:30:00'
    ),
    (
        4,
        5,
        'Are these headphones compatible with iOS?',
        '2024-11-21 10:30:00'
    ),
    (
        5,
        1,
        'What is the warranty period for this camera?',
        '2024-11-21 11:30:00'
    ),
    (
        6,
        2,
        'Can this desk be disassembled?',
        '2024-11-21 12:30:00'
    ),
    (
        7,
        3,
        'Is the chair still under warranty?',
        '2024-11-21 13:30:00'
    ),
    (
        8,
        4,
        'Can I inspect the sofa in person?',
        '2024-11-22 09:30:00'
    ),
    (
        9,
        5,
        'Is the bike suitable for off-road?',
        '2024-11-22 10:30:00'
    ),
    (
        10,
        1,
        'Does the treadmill have an incline feature?',
        '2024-11-22 11:30:00'
    ),
    (
        11,
        2,
        'What is the screen size of the smartwatch?',
        '2024-11-22 12:30:00'
    ),
    (
        12,
        3,
        'Is the necklace solid gold?',
        '2024-11-22 13:30:00'
    ),
    (
        13,
        4,
        'Can you provide a certificate for the diamond?',
        '2024-11-23 09:30:00'
    ),
    (
        14,
        5,
        'What size is the bracelet?',
        '2024-11-23 10:30:00'
    ),
    (
        15,
        1,
        'Are these shoes suitable for running?',
        '2024-11-23 11:30:00'
    ),
    (
        16,
        2,
        'Does the backpack have a laptop compartment?',
        '2024-11-23 12:30:00'
    ),
    (
        17,
        3,
        'What is the weight of the suitcase?',
        '2024-11-24 09:30:00'
    ),
    (
        18,
        4,
        'Does the TV come with a warranty?',
        '2024-11-24 10:30:00'
    ),
    (
        19,
        5,
        'Can you provide energy ratings for the microwave?',
        '2024-11-24 11:30:00'
    ),
    (
        20,
        1,
        'What are the dimensions of the refrigerator?',
        '2024-11-24 12:30:00'
    );

-- Inserting comment likes (without time field)
INSERT INTO
    `comment_likes` (`comment_id`, `buyer_id`)
VALUES
    (1, 2),
    (1, 3),
    (2, 1),
    (2, 4),
    (3, 5),
    (4, 1),
    (5, 2),
    (6, 3),
    (7, 4),
    (8, 5),
    (9, 1),
    (10, 2),
    (11, 3),
    (12, 4),
    (13, 5),
    (14, 1),
    (15, 2),
    (16, 3),
    (17, 4),
    (18, 5);