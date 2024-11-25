# Database Project Group 4

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

## Other Notices
### 1.ToDoList：
#### Done:
- [x] 所有搜索页面的num_bids 都还没改，醒来记得改（用tim这个名字注释了，可以在代码里搜）
- [x] 需要创建buyer和seller的单独表格，记得醒来创建一下
    - [x] 同时需要改下外键约束，让bids和auctions分别连接到buyer和seller表格上
- [x] login按钮不知道怎么失效了？？？
    - [x] 然后又莫名其妙好了？？？我的母语是无语
- [x] watchlist存在未知问题，无法插入数据
- [x] 已创建了buyer和seller，但注册功能输入两个表时会出错，记得改
- [x] watchlist有权限，只有buyer能watch，应当禁止seller账号竞拍和收藏，记得做

#### 仍需实现：
- auctions加入status(goingon/canceled/success)，判断可以展示到browse上的商品
- 邮件发信：大概需要添加的接口：auctions更新后将canceled/success 的邮件分别向参与其中的买卖家进行发送

#### 新的思考：
- browse或许可以同时进行精准或模糊搜索？（即让sql产生intersect和union的区别）

### 2.关于仍未实现的基本功能的一些提示与个人想法：

作业明确要求拍卖成交后需要award to the highest bidder, and confirm to both the winner and seller:
- 你们对于这两句说法对应的功能实现有何看法？

award是否对应着buyer需要credit属性或是额外的order表格？
confirm意思是发送邮件还是页面弹窗？可补充。。。
- 可能需要考虑时间到达以后对订单的状态设定函数（是否达到最低价，买卖家分别是谁）
    -额外组件1：建立页面查看已成交的订单 myorders.php（buyer和seller皆可访问）
    -额外组件2：在my listing/my bid/listing显示该物品状态（竞拍中/成交/流拍/已结束，即被其他买家拍走）
        -以上可以二选一，但无论实现以上哪种额外功能，均需要在auctions添加额外的status属性
    -是否需要监听listings上的时间变化，以确保时间到时商品页面能及时切换到已成交/已结束状态（或者直接刷新页面即可，那样就不考虑这个了）

### 3.关于仍未实现的额外功能的一些注解和看法：
- email发送功能和watchlist这个功能紧密绑定，也就是watch之后必定会收到邮件，也就是说邮件发送不需要额外的user.subscription属性判定发不发，而是watch后立即就向收藏者发送确认邮件， 并在每次新bid出现时再发送一次提醒邮件
- recommendations点明了使用协同过滤算法，简单来说即以余弦相似度为基础的预测公式来推送数据
    - 不知道你们本科学不学这个，但这个是推荐算法与语言模型最基础的东西之一，下学期选了大数据相关课的家人可以试试，只占5分，没那时间和能力就算了