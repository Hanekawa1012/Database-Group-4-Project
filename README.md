# Database Project Group 4 

## Report (该部分内容在typora或overleaf内单独生成pdf)

- URL for your Youtube video
- Your ERD, giving any assumptions that it makes about the processes that uses the data.
- A listing of your database schema (list of table names and attributes) with an explanatic of how it translates the ER diagram
- An analsis showing that the database schema is in third normal form.
- A listing and explanation of your database queries
- Other individual submission: 1) a self-assessment 2) peer assessment

## Notice(最后提交时以下内容全部删除)
### 1.ToDoList：

#### 仍需实现：
- mybid, mylisting, my watchlist 的每一栏是否需要改动？将bid history和auction history放在对应的商品页的下方，还是直接输出在mybid/mylisting 里面？（还是两个都做？：两个都做，而且多做两个按钮供用户选择）
- auctions加入status(goingon/canceled/success)，判断可以展示到browse上的商品
- 邮件发信范围：
    - 每次buyer添加新的商品到watchlist时，对将该商品添加至watchlist的buyer发送订阅成功邮件；
    - 每次buyer向商品发起bid之后，向所有watch该商品的buyer发送价格更新邮件，并在邮件中附带商品信息与最新价格（要不要也向seller发一份变动邮件？）sol：
   - 如果设定了reserveprice，则在bidprice首次超过时向卖家发送邮件
    - 当商品拍卖到期后，商品通过sql cronjob更新auctions status，并向seller与所有watch该商品的buyer发送交易结束邮件，并在邮件中附带商品状态与ending price，若endprice小于reservePrice则流拍，其余情况则交易成功（好像需要一个新的orders table来专门记录endPrice?：大概需要新表？仍然需要思考）
    - 验证码邮件

#### 新的思考：
- browse或许可以同时进行精准或模糊搜索？（即让sql产生intersect和union的区别）

### 2.关于仍未实现的基本功能的一些提示与个人想法：

- 可能需要考虑时间到达以后对订单的状态设定函数（是否达到最低价，买卖家分别是谁）
    -额外组件1：建立页面查看已成交的订单 myorders.php（buyer和seller皆可访问）
    -额外组件2：在my listing/my bid/listing显示该物品状态（竞拍中/成交/流拍/已结束，即被其他买家拍走）

## Commits

## First commit

- Added starter code

## Commits till 3:07, 16/11/2024 by TIM

- Modified process_registration, register, header
    - accomplished registration confirmation, input validity check
    - added a sign-up botton in the header
- All modified versions are in branch Tim

## Commit at 19:51, 17/11/2024 by TIM

- Modified login_results, header
    - accomplished login request, session data setting, checking login info validity
- Fixed error that new accounts will be detected existing in database
- Modified in branch Tim

## Commit at 22:46, 17/11/2024 by TIM

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
    -  to call that in your working php please type require_once("my_db_connect.php") at the beginning
- All changes in branch Tim.
- *Maybe I will upload my sql script for creating tables needed in index.php tomorrow morning, stay tuned guys...(zzz)

## Commit at 6:14, 18/11/2024 by TIM

- Modified browse, create_auction_result, listing, login_result, logout, my_db_connect, process_registration, watchlist_funcs
    - About the files modified above, the point is that the database is kind of applicated foreign key constraint and I added watchlist table to store new data of watchlist relationship between user and auctions.
    - Accomplished smooth watchlist adding/removing, added foreign key seller_id to auctions, added recording user_id to sessions
- Added pdo_db_connect, sql_script
    - These are for you to get along with different database connection and database initializations. If you just right to get started with the project, make use of them.:)
- All changes in branch Tim. Maybe it's time to merge my things since I have to look at PythonIntro. Don't hesitate to ask me if there are any problems in my code. =)

## Commit at 6:14, 18/11/2024 by TIM

- Modified listing, place_bid
    - accomplished bidding items
    - created table bids, updated in sql_script

## Commit at 6:11, 22/11/2024 by TIM

- Modified... a lot of things
    - In conclusion the item pages including mybids, mylistings, mywatchlist, browse are all accomplished basically, including showing items, filtering ans searching, and showing remaining time
- But after that a lot of other problems come out, so maybe you could check my thoughts below.

## Commit at 23:23, 25/11/2024 by TIM
- 不装了，让我们说中文
- 几乎重构了整个数据库，添加了buyer和seller的约束表格，并将其他相关的表都从user外键转移到了buyer和seller上，同时auctions还增删了一些属性（比如auctions里删除了currentPrice（想了一下不符合3nf就删了）以及添加了status，方便用于筛选已结束的auction来发邮件）所以，请记得看我更新的sql_script（笑）

## Commit at 2:30, 26/11/24 by Zhenghao
- 写了下个人信息编辑，耶！

## Commit at 10:06, 26/11/24 by Evan
- 添加模拟测试数据，正在和新版的sql_script比对...
- 要不要新增comments？评论区还可以添加点赞之类的功能
- browse.php修改进行中...
