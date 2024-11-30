# Database Project Group 4

## Report (该部分内容在 typora 或 overleaf 内单独生成 pdf)

- URL for your Youtube video
- Your ERD, giving any assumptions that it makes about the processes that uses the data.
- A listing of your database schema (list of table names and attributes) with an explanatic of how it translates the ER diagram
- An analsis showing that the database schema is in third normal form.
- A listing and explanation of your database queries
- Other individual submission: 1) a self-assessment 2) peer assessment

- Schema Listing:
  - **Entities:**
  - User:
    | Attributes | Type | Definition | Notes|
    |------------|------|------------|------|
    |user_id |int |unique id for each user|PK|

## Notice(最后提交时以下内容全部删除)

### 1.ToDoList：(自用，今天做完)

- 0）报告，优先级最高，完成草稿后给朋友们看下
- 1）密码加密功能
- 2）以及防 sql 注入函数
- 3）mybid 的错误输出需要修复（已完成），再加添加额外按钮以两种形式展示
- 4）listing 下的评论区与 bid history(已完成)
- 5）类型搜索栏好像还有问题，看看怎么个事
- 6）status 搜索需要额外加入搜索栏
  \*check 以下 may 那边的进度：表单验证以及 Multi_query（已完成）

#### 仍需实现：

- mybid, mylisting, my watchlist 的每一栏是否需要改动？将 bid history 和 auction history 放在对应的商品页的下方，还是直接输出在 mybid/mylisting 里面？（还是两个都做？：两个都做，而且多做两个按钮供用户选择）
  - by Evan: 我觉得是应该都做的，my bid 只显示自己的竞价吧，而且应该有一个“是否是最高竞价者”的显示；商品页下面应该有关于对应商品的所有 bid history（没理解错问题吧？
- auctions 加入 status(goingon/canceled/success)，判断可以展示到 browse 上的商品
  - by Evan: auctions 的 status 我把它默认设成 open（在拍），然后可以改成 scheduled（计划开拍），cancelled（取消），sold（已售），unsold（未售出，可能是无人竞拍或者没达到 reserved price）。

### 2.关于仍未实现的基本功能的一些提示与个人想法：

- 可能需要考虑时间到达以后对订单的状态设定函数（是否达到最低价，买卖家分别是谁） -额外组件 1：建立页面查看已成交的订单 myorders.php（buyer 和 seller 皆可访问） - by Evan: 我觉得用不到，直接在 mybid 页面加一个 filtering -额外组件 2：在 my listing/my bid/listing 显示该物品状态（竞拍中/成交/流拍/已结束，即被其他买家拍走） - by Evan: 要做！！！我觉得应该直接在显示剩余时间的位置

## Commits

## Commit at 23:23, 25/11/2024 by TIM

- 不装了，让我们说中文
- 几乎重构了整个数据库，添加了 buyer 和 seller 的约束表格，并将其他相关的表都从 user 外键转移到了 buyer 和 seller 上，同时 auctions 还增删了一些属性（比如 auctions 里删除了 currentPrice（想了一下不符合 3nf 就删了）以及添加了 status，方便用于筛选已结束的 auction 来发邮件）所以，请记得看我更新的 sql_script（笑）

## Commit at 6:11, 22/11/2024 by TIM

- Modified... a lot of things
  - In conclusion the item pages including mybids, mylistings, mywatchlist, browse are all accomplished basically, including showing items, filtering ans searching, and showing remaining time
- But after that a lot of other problems come out, so maybe you could check my thoughts below.

- Modified process_registration, register, header
  - accomplished registration confirmation, input validity check
  - added a sign-up botton in the header
- All modified versions are in branch Tim

- Modified listing, place_bid

  - accomplished bidding items
  - created table bids, updated in sql_script

- Modified login_results, header
  - accomplished login request, session data setting, checking login info validity
- Fixed error that new accounts will be detected existing in database
- Modified in branch Tim

- Modified browse, create_auction_result, listing, login_result, logout, my_db_connect, process_registration, watchlist_funcs
  - About the files modified above, the point is that the database is kind of applicated foreign key constraint and I added watchlist table to store new data of watchlist relationship between user and auctions.
  - Accomplished smooth watchlist adding/removing, added foreign key seller_id to auctions, added recording user_id to sessions
- Added pdo_db_connect, sql_script
  - These are for you to get along with different database connection and database initializations. If you just right to get started with the project, make use of them.:)
- All changes in branch Tim. Maybe it's time to merge my things since I have to look at PythonIntro. Don't hesitate to ask me if there are any problems in my code. =)

- Modified logout, login_result, process_registration, header
  - finished session setting and unsetting in login/logout, now the website must go smoothly with them.
  - deleted FIXME given by doctor since it no more works for main page test
- might look back later, but now start working on item pages
- All changes in branch Tim, as usual.

## Commit at 2:12, 17/11/2024 by TIM

- Modified create_auction_resuls, create_auction
  - accomplished basic auction creation, including checking validity, inserting into database
- Added my_db_connect
  - this is a sql connection file to resist cost you type connection code block again and again
  - to call that in your working php please type require_once("my_db_connect.php") at the beginning
- All changes in branch Tim.
- \*Maybe I will upload my sql script for creating tables needed in index.php tomorrow morning, stay tuned guys...(zzz)

## Commit at 22:46, 17/11/2024 by TIM

- Modified browse, create_auction_result, listing, login_result, logout, my_db_connect, process_registration, watchlist_funcs
  - About the files modified above, the point is that the database is kind of applicated foreign key constraint and I added watchlist table to store new data of watchlist relationship between user and auctions.
  - Accomplished smooth watchlist adding/removing, added foreign key seller_id to auctions, added recording user_id to sessions
- Added pdo_db_connect, sql_script
  - These are for you to get along with different database connection and database initializations. If you just right to get started with the project, make use of them.:)
- All changes in branch Tim. Maybe it's time to merge my things since I have to look at PythonIntro. Don't hesitate to ask me if there are any problems in my code. =)

## Commit at 19:51, 17/11/2024 by TIM

- Modified listing, place_bid
  - accomplished bidding items
  - created table bids, updated in sql_script

## Commits till 3:07, 16/11/2024 by TIM

- Modified... a lot of things
  - In conclusion the item pages including mybids, mylistings, mywatchlist, browse are all accomplished basically, including showing items, filtering ans searching, and showing remaining time
- But after that a lot of other problems come out, so maybe you could check my thoughts below.

## Commit at 23:23, 25/11/2024 by TIM

- 不装了，让我们说中文
- 几乎重构了整个数据库，添加了 buyer 和 seller 的约束表格，并将其他相关的表都从 user 外键转移到了 buyer 和 seller 上，同时 auctions 还增删了一些属性（比如 auctions 里删除了 currentPrice（想了一下不符合 3nf 就删了）以及添加了 status，方便用于筛选已结束的 auction 来发邮件）所以，请记得看我更新的 sql_script（笑）

## Commit at 2:30, 26/11/24 by Zhenghao

- 写了下个人信息编辑，耶！

## Commit at 10:06, 26/11/24 by Evan

- 添加模拟测试数据，正在和新版的 sql_script 比对...
- 要不要新增 comments？评论区还可以添加点赞之类的功能
- browse.php 修改进行中...

## Commit at 21:00, 26/11/24 by Zhenghao

- 实现了忘记密码验证邮箱验证码后重置密码，并以类似的机制实现了变更密码功能（Tim：GOOD JOB，还缺了一些组件，我添加上来）

## Commit at 1:42, 27/11/2024 by Evan

- 将所有的文件的缩进、sql 语句格式、注释等格式统一调整
- 修改了部分邮件发送格式
- 添加新的模拟数据
- 添加了 footer
- 关于 header：（Tim：WELL DONE 帮大忙 质感一下就上来了）
  - 添加模糊搜索选项
  - 将搜索栏重做，现在三个搜索选项下拉框被装入了一个 Advanced search 隐藏栏
  - 搜索的 category 现在会随数据库实时变化
  - 添加了 JavaScript 语句，让搜索栏/下拉框和 URL 中的 metadata 保持同步
- 修正了 browse.php 的一些问题
- 添加了 recommendation，利用**余弦相似度**完成相似度检测。最多展示 10 条结果

  (_TODO_: by Evan 大样本量测试推荐系统的正确性。现在的状况，只能说“看起来”是正确的)

- 在 listing 界面添加了一些注释，提示添加标签页，分别展示商品详情/竞拍记录/评论区
- _TODO_：添加 comments？(Tim: 求放过（bushi，但是真的想做的话浅浅规划下，主要是添加新的 comment 表，独立主键，引入 item 和 buyer 的 id 为外键，最后是 comment 的具体内容和发布时间)（网页部分则是在 listing 下加额外 div 显示对应评论）

  (Evan: 我感觉其实还有时间。我现在的思路是，comment 表应该包含发送时间，点赞数和子评论，schema 大概应该是如下。)

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

（Tim: 除此以外，做了 comment 之后，recommend 可能需要考虑买家发布评论的交易分布作为新的权重）(Evan: 我觉得合理，不过推荐就会超级复杂，到时候再说)

- _TODO_：商品浏览页（browse 等）展示 status？（Tim：老师的 utilities 里的 print 函数已经有相关的 if 判断，不过那是基于结束时间的，我们可以在 print 函数额外加一个 status 传进去，把它的 if 判断基于 status 属性弄得更复杂些）（同时修改所有相关页面，即 mybid，browse 等的 sql，多搜一个 status）（Evan: EXACTLY! 就是这个意思)

## Commit until 7:19, 27/11/2024 by Tim

- 修改了 send_email.php 的函数，实现向多用户发信的功能，email 和 name 可传入数组或名称，具体群发写法可参照我的 place_bid.php(如果需要的话)
- 修改了 header 和编辑信息的一些逻辑，现在登录时需要额外选择买卖家的身份，与此相对的，email 不再严格要求不重复，以在提升观感同时满足 3rd normal form 条件

## Commit until 11:11, 28/11/2024 by May

- （创建分支 debug）
- 修复了注册、登陆等页面的跳转问题
- 添加注册、登陆输入的前端表单验证
- 更改部分代码写法，保持原有功能的情况下提高可读性

## Commit until 0:15, 30/11/2024 by May

- 添加 bid 的前端表单验证
- 修复 watching 数据库表格查询/删除错误时，提示语句重复出现的 bug
- 查找到 add to watch 按钮无法正常切换的问题（出在邮件发送上，详见 trello）；现暂时注释掉了 watchlist_funcs.php 中的相关代码
- 修复部分 bug
