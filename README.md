# Requirement Ticklists:

- Users can 
  - register with the system 
  - and create accounts. 
- Users have roles of seller or buyer with different privileges. 
- Sellers can 
  - create auctions for particular items, 
  - setting suitable conditions and features of the items including the item description, categorisation, starting price, reserve price and end date. 
- Buyers can 
  - search the system for particular kinds of item being auctioned and can 
  - browse and visually re-arrange listings of items within categories. 
- Buyers can bid for items and see the bids other users make as they are received. The system will manage the auction until the set end time and award the item to the highest bidder. The system should confirm to both the winner and seller of an auction its outcome. 
- Extra functionality related to core features requiring usage of a database.
- Buyers can 
  - watch auctions on items and 
  - receive emailed updates on bids on those items including notifications when they are outbid.
- Buyers can 
  - receive recommendations for items to bid on based on collaborative filtering (i.e., ‘you might want to bid on the sorts of things other people, who have also bid on the sorts of things you have previously bid on, are currently bidding on). 




# Database Project Group 4 Report (该部分内容在typora或overleaf内单独生成pdf)

- URL for your Youtube video
- Your ERD, giving any assumptions that it makes about the processes that uses the data.
- A listing of your database schema (list of table names and attributes) with an explanatic of how it translates the ER diagram
- An analsis showing that the database schema is in third normal form.
- A listing and explanation of your database queries
- Other individual submission: 1) a self-assessment 2) peer assessment

## Youtube Link for Demo Video:

## ER Diagram and Assumptions

## Schema Listing:

### User Table
| Attribute    | Data Type         | Note                | Definition                     |
|--------------|-------------------|---------------------|--------------------------------|
| `user_id`    | INT               | PK, AUTO_INCREMENT  | User ID                        |
| `password`   | VARCHAR(100)      | NOT NULL            | User password                  |
| `email`      | VARCHAR(100)      | NOT NULL, UNIQUE    | User email                     |
| `accountType`| ENUM('buyer', 'seller') | NOT NULL       | Type of account (buyer/seller) |

### Profile Table
| Attribute    | Data Type         | Note                | Definition                     |
|--------------|-------------------|---------------------|--------------------------------|
| `user_id`    | INT               | PK, FK              | User ID                        |
| `username`   | VARCHAR(50)       | NOT NULL            | Username                       |
| `tel`        | VARCHAR(15)       | NOT NULL            | Telephone number               |
| `address`    | VARCHAR(100)      | NOT NULL, UNIQUE    | Address                        |

### Buyer Table
| Attribute    | Data Type         | Note                | Definition                     |
|--------------|-------------------|---------------------|--------------------------------|
| `user_id`    | INT               | PK, FK              | User ID                        |

### Seller Table
| Attribute    | Data Type         | Note                | Definition                     |
|--------------|-------------------|---------------------|--------------------------------|
| `user_id`    | INT               | PK, FK              | User ID                        |             

### Auctions Table
| Attribute      | Data Type         | Note                | Definition                     |
|----------------|-------------------|---------------------|--------------------------------|
| `item_id`      | INT               | PK, AUTO_INCREMENT  | Item ID                        |
| `title`        | VARCHAR(50)       | CK, UNIQUE    | Auction title                  |
| `details`      | TEXT              | NOT NULL                    | Auction details                |
| `category`     | VARCHAR(30)       | NOT NULL            | Auction category               |
| `startPrice`   | DECIMAL(10, 2)    | NOT NULL            | Starting price                 |
| `reservePrice` | DECIMAL(10, 2)    | CHECK (reservePrice IS NULL OR reservePrice >= startPrice) | Reserve price                  |
| `startDate`    | DATETIME          | NOT NULL            | Auction start date             |
| `endDate`      | DATETIME          | NOT NULL            | Auction end date               |
| `seller_id`    | INT               | NOT NULL, FK        | Seller ID                      |
| `status`       | ENUM('active', 'closed', 'cancelled') | NOT NULL | Auction status        |

### Watchlist Table
| Attribute    | Data Type         | Note                | Definition                     |
|--------------|-------------------|---------------------|--------------------------------|
| `list_id`    | INT               | PK, AUTO_INCREMENT  | List ID                        |
| `buyer_id`   | INT               | NOT NULL, FK        | Buyer ID                       |
| `item_id`    | INT               | NOT NULL, FK        | Item ID                        |

### Bids Table
| Attribute    | Data Type         | Note                | Definition                     |
|--------------|-------------------|---------------------|--------------------------------|
| `bid_id`     | INT               | PK, AUTO_INCREMENT  | Bid ID                         |
| `buyer_id`   | INT               | NOT NULL, FK        | Buyer ID                       |
| `item_id`    | INT               | NOT NULL, FK        | Item ID                        |
| `bidPrice`   | DECIMAL(10, 2)    | NOT NULL            | Bid price                      |
| `bidTime`    | DATETIME          | NOT NULL            | Bid time                       |

### Comments Table
| Attribute          | Data Type         | Note                | Definition                     |
|--------------------|-------------------|---------------------|--------------------------------|
| `comment_id`       | INT               | PK, AUTO_INCREMENT  | Comment ID                     |
| `item_id`          | INT               | NOT NULL, FK        | Item ID                        |
| `buyer_id`         | INT               | NOT NULL, FK        | Buyer ID                       |
| `time`             | DATETIME          | NOT NULL            | Comment time                   |
| `content`          | VARCHAR(1023)     | NOT NULL            | Comment content                |
| `parent_comment_id`| INT               | FK                  | Parent comment ID              |

### Comment Likes Table
| Attribute    | Data Type         | Note                | Definition                     |
|--------------|-------------------|---------------------|--------------------------------|
| `comment_id` | INT               | FK              | Comment ID                     |
| `buyer_id`   | INT               | FK              | Buyer ID                       |

## All SQL Query Listings Sorted by PHP Files:

### Search Bar Related
**MAIN FUNCTIONS: collaborate with search bar form input to show items in customized ways**

#### browse.php as example, and logically same as mybids.php, mylistings.php, mywatchlist.php:

1. **SQL Statement:**
   ```sql
   $sql = "SELECT * FROM auctions";
   ```
   **Explanation:** This query selects all columns from the `auctions` table. It serves as the base query for retrieving auction listings.

2. **SQL Statement:**
   ```sql
   if (isset($_GET['keyword']) || $keyword != "") {
       $sql .= " WHERE (title LIKE '%$keyword%' OR details LIKE '%$keyword%')";
       if ($category != "") {
           if ($search_type == "union") {
               $sql .= " UNION SELECT * FROM auctions WHERE category = '$category'";
           } else {
               $sql .= " AND category = '$category'";
           }
       }
   } else if ($category != "") {
       $sql .= " WHERE category = '$category'";
   }
   ```
   **Explanation:** This block modifies the base query to filter results based on the `keyword` and `category` parameters from the URL. If a keyword is provided, it searches for auctions where the title or details contain the keyword. If a category is also provided, it either adds it to the existing filter (for intersection search) or performs a union with another query filtering by category (for union search).

3. **SQL Statement:**
   ```sql
   if ($ordering != "") {
       $sql .= " ORDER BY $ordering";
   }
   ```
   **Explanation:** This adds an `ORDER BY` clause to the query to sort the results based on the `order_by` parameter from the URL.

4. **SQL Statement:**
   ```sql
   $result_q = mysqli_query($con, $sql);
   ```
   **Explanation:** This executes the constructed SQL query and stores the result set in `$result_q`.

5. **SQL Statement:**
   ```sql
   $num_results = mysqli_num_rows($result_q);
   ```
   **Explanation:** This retrieves the number of rows in the result set, which is used for pagination.

6. **SQL Statement:**
   ```sql
   if ($curr_page != "") {
       $sql .= " LIMIT " . (($curr_page - 1) * $results_per_page) . ", $results_per_page";
   }
   $result_q = mysqli_query($con, $sql);
   ```
   **Explanation:** This adds a `LIMIT` clause to the query to paginate the results, fetching only a subset of rows based on the current page number. The query is then re-executed with the limit applied.

7. **SQL Statement:**
   ```sql
   $bid_sql = "SELECT bidPrice FROM bids WHERE item_id = $item_id ORDER BY bidPrice DESC";
   $bid_result = mysqli_query($con, $bid_sql);
   ```
   **Explanation:** This query retrieves the highest bid price for a specific auction item from the `bids` table, ordered in descending order by bid price.

8. **SQL Statement:**
   ```sql
   $num_bids = mysqli_num_rows($bid_result);
   ```
   **Explanation:** This retrieves the number of bids for the specific auction item.

These SQL statements are used to dynamically construct and execute queries based on user input from the URL parameters, allowing for flexible searching, filtering, and pagination of auction listings. 

### Account Management Related 
**FILES:**
**MAIN FUNCTIONS: register, login, edit peronal profile, change password, send verification code**

#### login_result.php

1. **SQL Statement:**
   ```sql
   $sql = "SELECT * FROM user WHERE email = '$email' AND password = SHA('$password') AND accountType = '$accountType';";
   ```
   **Explanation:** This query selects all columns from the `user` table where the `email`, `password` (hashed using the `SHA` function), and `accountType` match the provided values. It is used to verify the user's login credentials.

2. **SQL Statement:**
   ```sql
   $sql_profile = "SELECT * FROM profile WHERE user_id = '$user_id';";
   ```
   **Explanation:** This query selects all columns from the `profile` table where the `user_id` matches the provided user ID. It is used to retrieve the user's profile information after successful login.

These SQL statements are used to authenticate the user by checking their email, password, and account type, and then to retrieve their profile information if the login is successful.

#### forget_password.php

1. **SQL Statement:**
   ```sql
   $sql = "SELECT user_id FROM user WHERE email = '$email'";
   ```
   **Explanation:** This query selects the `user_id` from the `user` table where the `email` matches the provided email address. It is used to check if a user with the given email exists.

2. **SQL Statement:**
   ```sql
   $sql_username = "SELECT username FROM profile WHERE user_id = '$user_id';";
   ```
   **Explanation:** This query selects the `username` from the `profile` table where the `user_id` matches the provided user ID. It is used to retrieve the username associated with the user ID.

3. **SQL Statement:**
   ```sql
   $sql_update_password = "UPDATE user SET password = SHA('$new_password') WHERE user_id = $user_id";
   ```
   **Explanation:** This query updates the `password` column in the `user` table for the user with the specified `user_id`. The new password is hashed using the `SHA` function before being stored in the database.

These SQL statements are used to verify the existence of a user by email, retrieve the username associated with the user ID, and update the user's password in the database after verification.

#### process_edit.php

1. **Checking for existing email:**
   ```php
   $check_email_sql = "SELECT user_id FROM user WHERE email = '$email' AND user_id != '$user_id' AND accountType = '$accountType'";
   ```
   - **Purpose:** This query checks if the provided email address is already registered to another user with the same account type. If it finds a match, it prevents the email from being used again.

2. **Fetching user profile information before editing:**
   ```php
   $sql_info_before_edit = "SELECT * FROM profile WHERE user_id = '$user_id';";
   ```
   - **Purpose:** This query retrieves the current profile information (telephone and address) of the user before any updates are made. This is used to compare and determine if any changes have been made.

3. **Updating user information:**
   ```php
   $sql_user = "UPDATE user SET ";
   ```
   - **Purpose:** This is the base of the SQL update statement for the `user` table. It will be appended with specific fields to update if there are any changes.

4. **Updating profile information:**
   ```php
   $sql_profile = "UPDATE profile SET ";
   ```
   - **Purpose:** This is the base of the SQL update statement for the `profile` table. It will be appended with specific fields to update if there are any changes.

5. **Executing the update for user table:**
   ```php
   $sql_user .= implode(", ", $updates_user);
   $sql_user .= " WHERE user_id = '$user_id'";
   $result_edit_user = $con->query($sql_user);
   ```
   - **Purpose:** This query updates the `user` table with the new email if it has been changed. It constructs the full SQL statement by appending the changes and executes it.

6. **Executing the update for profile table:**
   ```php
   $sql_profile .= implode(", ", $updates_profile);
   $sql_profile .= " WHERE user_id = '$user_id'";
   $result_edit_profile = $con->query($sql_profile);
   ```
   - **Purpose:** This query updates the `profile` table with the new username, telephone, and address if they have been changed. It constructs the full SQL statement by appending the changes and executes it.

These SQL statements are used to ensure that the user's profile information is correctly updated in the database while checking for any conflicts, such as duplicate email addresses. If no changes are detected, the script informs the user that no updates were made.

#### change_password.php

1. **SQL Statement:**
   ```sql
   $sql = "UPDATE user SET password = SHA('$new_password') WHERE user_id = '$user_id'";
   ```
   **Explanation:** This query updates the `password` column in the `user` table for the user with the specified `user_id`. The new password is hashed using the `SHA` function before being stored in the database.

These SQL statements are used to update the user's password in the database after verifying the user's identity through a verification code sent to their email.

### Buyer/Bids Related
**MAIN FUNCTIONS: watch, bid for an item, receive outbid or status update notifications of bidded/watching items**

#### place_bid.php

1. **SQL Statement:**
   ```sql
   $sql = "INSERT INTO bids (buyer_id, item_id, bidPrice, bidTime) 
           VALUES ($user_id, $item_id, '$bidPrice', '$bidTime');";
   ```
   **Explanation:** This query inserts a new record into the `bids` table with the specified values for `buyer_id`, `item_id`, `bidPrice`, and `bidTime`. This is used to record a new bid made by a user on an auction item.

2. **SQL Statement:**
   ```sql
   $sql_item = "SELECT title, details, category, endDate FROM auctions WHERE item_id = $item_id;";
   ```
   **Explanation:** This query selects the `title`, `details`, `category`, and `endDate` columns from the `auctions` table for the auction with the specified `item_id`. This information is used to provide details about the auction item for email notifications.

3. **SQL Statement:**
   ```sql
   $sql_watching = "SELECT email FROM user WHERE user_id IN
                    (SELECT buyer_id FROM watchlist WHERE item_id = $item_id);";
   ```
   **Explanation:** This query selects the `email` addresses from the `user` table for users who have the specified `item_id` in their `watchlist`. This is used to notify all users watching the auction about the new bid.

These SQL statements are used to insert a new bid into the database, retrieve auction item details for email notifications, and get the email addresses of users watching the auction to notify them of the new bid.

#### user_info.php

1. **Fetching user profile information:**
   ```php
   $sql = "SELECT * FROM profile WHERE user_id = '$user_id';";
   ```
   - **Purpose:** This query retrieves all the profile information for the user with the specified `user_id`. It is used to fetch the user's telephone number and address, which are then displayed on the profile page.

This SQL statement ensures that the user's profile information is correctly retrieved from the database and displayed on the profile page. If the user has not set their telephone number or address, the page will prompt them to update their profile.

### Seller/Auctions Related
**MAIN FUNCTIONS: create, cancel, update status of auctions**

#### create_auction_result.php

1. **SQL Statement:**
   ```sql
   $sql = "INSERT INTO auctions(title, details, category, startPrice, reservePrice, startDate, endDate, seller_id) 
           VALUES ('$auctionTitle', '$auctionDetails', '$auctionCategory', '$auctionStartPrice', 
                   '$auctionReservePrice', NOW(), '$auctionEndDate', '$auctionSellerID');";
   ```
   **Explanation:** This query inserts a new record into the `auctions` table with the specified values for `title`, `details`, `category`, `startPrice`, `reservePrice`, `startDate` (set to the current date and time using `NOW()`), `endDate`, and `seller_id`. This is used to create a new auction listing in the database.

2. **SQL Statement:**
   ```sql
   if (mysqli_query($con, $sql)) {
       echo "Data insert succeed.\n";
   } else {
       echo "Data insert failed.\n" . "<br/>" . $con->error;
   }
   ```
   **Explanation:** This block executes the previously constructed SQL `INSERT` statement. If the query is successful, it outputs a success message. If the query fails, it outputs an error message along with the error details.

These SQL statements are used to add a new auction listing to the database, ensuring that all necessary data is provided and valid before insertion.

#### cancel_auction.php:

1. **SQL Statement:**
   ```sql
   $sql = "UPDATE auctions SET status = 3 WHERE item_id = $item_id;";
   ```
   **Explanation:** This query updates the `auctions` table, setting the `status` column to `3` (which likely represents a cancelled status) for the auction with the specified `item_id`.

2. **SQL Statement:**
   ```sql
   $sql_item = "SELECT title, details, category, endDate FROM auctions WHERE item_id = $item_id;";
   ```
   **Explanation:** This query selects the `title`, `details`, `category`, and `endDate` columns from the `auctions` table for the auction with the specified `item_id`. This information is used to provide details about the cancelled auction.

These SQL statements are used to update the status of an auction to cancelled and to retrieve the details of the auction for confirmation and notification purposes. 

### Daily Notification/ Watchlist Related

#### watchlist_func.php

1. **Adding an item to the watchlist:**
   ```php
   $sql = "INSERT INTO watchlist (buyer_id, item_id) VALUES ($buyer_id, $item_id);";
   ```
   - **Purpose:** This query inserts a new record into the `watchlist` table, associating the current user (`buyer_id`) with the specified item (`item_id`). It adds the item to the user's watchlist.

2. **Fetching item details:**
   ```php
   $sql_item = "SELECT title, details, category, endDate FROM auctions WHERE item_id = $item_id;";
   ```
   - **Purpose:** This query retrieves the details of the specified item from the `auctions` table. It fetches the title, details, category, and end date of the item that has been added to the watchlist.

3. **Removing an item from the watchlist:**
   ```php
   $sql = "DELETE FROM watchlist WHERE buyer_id = $buyer_id AND item_id = $item_id;";
   ```
   - **Purpose:** This query deletes the record from the `watchlist` table where the `buyer_id` and `item_id` match the specified values. It removes the item from the user's watchlist.

These SQL statements are used to manage the user's watchlist by adding or removing items and fetching item details for notifications. If there are any issues with these operations, the script returns an error message.

#### send_end_email.php

1. **Selecting emails of sellers and buyers for outdated auctions:**
   ```php
   $sql = "SELECT email FROM user WHERE user_id IN
           (SELECT seller_id FROM auctions WHERE endDate <= '$currentDate' AND status = 'active')
           UNION ALL
           SELECT email FROM user WHERE user_id IN
           (SELECT buyer_id FROM watchlist WHERE item_id IN
           (SELECT item_id FROM auctions WHERE endDate <= '$currentDate' AND status = 'active'))";
   ```
   - **Purpose:** This query retrieves the email addresses of users who are either sellers of auctions that have ended or buyers who are watching these auctions. It uses a `UNION ALL` to combine the results from two subqueries:
     - The first subquery selects emails of sellers whose auctions have ended.
     - The second subquery selects emails of buyers who are watching items in auctions that have ended.

2. **Updating the status of outdated auctions:**
   ```php
   $updateSql = "UPDATE auctions SET status = 'closed' WHERE endDate <= '$currentDate' AND status = 'active'";
   ```
   - **Purpose:** This query updates the status of auctions that have ended by setting their status to 'closed'. It ensures that these auctions are marked as no longer active.

These SQL statements are used to identify users who need to be notified about the end of auctions and to update the auction status accordingly. If there are any outdated auctions, the script sends notification emails to the relevant users and updates the auction status in the database.

## Notice(最后提交时以下内容全部删除)
### 1.ToDoList：(自用，今天做完)
- 报告，优先级最高，完成草稿后给朋友们看下
- 添加额外按钮以两种形式展示bid/auctions history
- 类型搜索栏好像还有问题，看看怎么个事
- status搜索需要额外加入搜索栏
- 表单验证

#### 仍需实现：
- mybid, mylisting, my watchlist 的每一栏是否需要改动？将bid history和auction history放在对应的商品页的下方，还是直接输出在mybid/mylisting 里面？（还是两个都做？：两个都做，而且多做两个按钮供用户选择）
  - by Evan: 我觉得是应该都做的，my bid只显示自己的竞价吧，而且应该有一个“是否是最高竞价者”的显示；商品页下面应该有关于对应商品的所有bid history（没理解错问题吧？
- auctions加入status(goingon/canceled/success)，判断可以展示到browse上的商品
  - by Evan: auctions的status我把它默认设成open（在拍），然后可以改成scheduled（计划开拍），cancelled（取消），sold（已售），unsold（未售出，可能是无人竞拍或者没达到reserved price）。
### 2.关于仍未实现的基本功能的一些提示与个人想法：

- 可能需要考虑时间到达以后对订单的状态设定函数（是否达到最低价，买卖家分别是谁）
    -额外组件1：建立页面查看已成交的订单 myorders.php（buyer和seller皆可访问）
      - by Evan: 我觉得用不到，直接在mybid页面加一个filtering
    -额外组件2：在my listing/my bid/listing显示该物品状态（竞拍中/成交/流拍/已结束，即被其他买家拍走）
      - by Evan: 要做！！！我觉得应该直接在显示剩余时间的位置

## Commits

## Commit until 7:19, 27/11/2024 by Tim
- 修改了send_email.php的函数，实现向多用户发信的功能，email和name可传入数组或名称，具体群发写法可参照我的place_bid.php(如果需要的话)
- 修改了header和编辑信息的一些逻辑，现在登录时需要额外选择买卖家的身份，与此相对的，email不再严格要求不重复，以在提升观感同时满足3rd normal form条件

## Commit at 1:42, 27/11/2024 by Evan
- 将所有的文件的缩进、sql语句格式、注释等格式统一调整
- 修改了部分邮件发送格式
- 添加新的模拟数据
- 添加了footer
- 关于header：（Tim：WELL DONE 帮大忙 质感一下就上来了）
    - 添加模糊搜索选项
    - 将搜索栏重做，现在三个搜索选项下拉框被装入了一个Advanced search隐藏栏
    - 搜索的category现在会随数据库实时变化
    - 添加了JavaScript语句，让搜索栏/下拉框和URL中的metadata保持同步
- 修正了browse.php的一些问题
- 添加了recommendation，利用**余弦相似度**完成相似度检测。最多展示10条结果

  (*TODO*: by Evan 大样本量测试推荐系统的正确性。现在的状况，只能说“看起来”是正确的)
- 在listing界面添加了一些注释，提示添加标签页，分别展示商品详情/竞拍记录/评论区
- *TODO*：添加comments？(Tim: 求放过（bushi，但是真的想做的话浅浅规划下，主要是添加新的comment表，独立主键，引入item和buyer的id为外键，最后是comment的具体内容和发布时间)（网页部分则是在listing下加额外div显示对应评论）

  (Evan: 我感觉其实还有时间。我现在的思路是，comment表应该包含发送时间，点赞数和子评论，schema大概应该是如下。)
```sql
CREATE TABLE `comments` (
  `comment_id` INT PRIMARY KEY AUTO_INCREMENT,
  `item_id` INT NOT NULL,
  `commenter_id` INT NOT NULL,
  `time` DATETIME NOT NULL,
  `content` VARCHAR(1023) NOT NULL,
  `parent_comment_id` INT,

  FOREIGN KEY (`item_id`) REFERENCES `auction` (`item_id`) ON DELETE CASCADE,
  FOREIGN KEY (`commenter_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`parent_comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE
);

CREATE TABLE `comment_likes` (
  `comment_id` INT NOT NULL,
  `buyer_id` INT NOT NULL,

  FOREIGN KEY (`comment_id`) REFERENCES `comment` (`comment_id`) ON DELETE CASCADE,
  FOREIGN KEY (`buyer_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE
);
```

（Tim: 除此以外，做了comment之后，recommend可能需要考虑买家发布评论的交易分布作为新的权重）(Evan: 我觉得合理，不过推荐就会超级复杂，到时候再说)

- *TODO*：商品浏览页（browse等）展示status？（Tim：老师的utilities里的print函数已经有相关的if判断，不过那是基于结束时间的，我们可以在print函数额外加一个status传进去，把它的if判断基于status属性弄得更复杂些）（同时修改所有相关页面，即mybid，browse等的sql，多搜一个status）（Evan: EXACTLY! 就是这个意思)

## Commit at 21:00, 26/11/24 by Zhenghao
- 实现了忘记密码验证邮箱验证码后重置密码，并以类似的机制实现了变更密码功能（Tim：GOOD JOB，还缺了一些组件，我添加上来）

## Commit at 10:06, 26/11/24 by Evan
- 添加模拟测试数据，正在和新版的sql_script比对...
- 要不要新增comments？评论区还可以添加点赞之类的功能
- browse.php修改进行中...

## Commit at 2:30, 26/11/24 by Zhenghao
- 写了下个人信息编辑，耶！

## Commit at 23:23, 25/11/2024 by TIM
- 不装了，让我们说中文
- 几乎重构了整个数据库，添加了buyer和seller的约束表格，并将其他相关的表都从user外键转移到了buyer和seller上，同时auctions还增删了一些属性（比如auctions里删除了currentPrice（想了一下不符合3nf就删了）以及添加了status，方便用于筛选已结束的auction来发邮件）所以，请记得看我更新的sql_script（笑）

## Commit at 6:11, 22/11/2024 by TIM

- Modified... a lot of things
    - In conclusion the item pages including mybids, mylistings, mywatchlist, browse are all accomplished basically, including showing items, filtering ans searching, and showing remaining time
- But after that a lot of other problems come out, so maybe you could check my thoughts below.

## Commit at 6:14, 18/11/2024 by TIM

- Modified listing, place_bid
    - accomplished bidding items
    - created table bids, updated in sql_script

## Commit at 6:14, 18/11/2024 by TIM

- Modified browse, create_auction_result, listing, login_result, logout, my_db_connect, process_registration, watchlist_funcs
    - About the files modified above, the point is that the database is kind of applicated foreign key constraint and I added watchlist table to store new data of watchlist relationship between user and auctions.
    - Accomplished smooth watchlist adding/removing, added foreign key seller_id to auctions, added recording user_id to sessions
- Added pdo_db_connect, sql_script
    - These are for you to get along with different database connection and database initializations. If you just right to get started with the project, make use of them.:)
- All changes in branch Tim. Maybe it's time to merge my things since I have to look at PythonIntro. Don't hesitate to ask me if there are any problems in my code. =)


## Commit at 2:12, 17/11/2024 by TIM

- Modified create_auction_resuls, create_auction
    - accomplished basic auction creation, including checking validity, inserting into database
- Added my_db_connect
    - this is a sql connection file to resist cost you type connection code block again and again
    -  to call that in your working php please type require_once("my_db_connect.php") at the beginning
- All changes in branch Tim.
- *Maybe I will upload my sql script for creating tables needed in index.php tomorrow morning, stay tuned guys...(zzz)

## Commit at 22:46, 17/11/2024 by TIM

- Modified logout, login_result, process_registration, header
    - finished session setting and unsetting in login/logout, now the website must go smoothly with them.
    - deleted FIXME given by doctor since it no more works for main page test
- might look back later, but now start working on item pages
- All changes in branch Tim, as usual.

## Commit at 19:51, 17/11/2024 by TIM

- Modified login_results, header
    - accomplished login request, session data setting, checking login info validity
- Fixed error that new accounts will be detected existing in database
- Modified in branch Tim

## Commits till 3:07, 16/11/2024 by TIM

- Modified process_registration, register, header
    - accomplished registration confirmation, input validity check
    - added a sign-up botton in the header
- All modified versions are in branch Tim

## First commit

- Added starter code
