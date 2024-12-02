## Notice(最后提交时以下内容全部删除)

### 1.ToDoList：(自用，今天做完)

- 报告，优先级最高，完成草稿后给朋友们看下
- 添加额外按钮以两种形式展示 bid/auctions history
- 类型搜索栏好像还有问题，看看怎么个事
- status 搜索需要额外加入搜索栏
- 表单验证

#### 仍需实现：

- mybid, mylisting, my watchlist 的每一栏是否需要改动？将 bid history 和 auction history 放在对应的商品页的下方，还是直接输出在 mybid/mylisting 里面？（还是两个都做？：两个都做，而且多做两个按钮供用户选择）
  - by Evan: 我觉得是应该都做的，my bid 只显示自己的竞价吧，而且应该有一个“是否是最高竞价者”的显示；商品页下面应该有关于对应商品的所有 bid history（没理解错问题吧？
- auctions 加入 status(goingon/canceled/success)，判断可以展示到 browse 上的商品
  - by Evan: auctions 的 status 我把它默认设成 open（在拍），然后可以改成 scheduled（计划开拍），cancelled（取消），sold（已售），unsold（未售出，可能是无人竞拍或者没达到 reserved price）。

### 2.关于仍未实现的基本功能的一些提示与个人想法：

- 可能需要考虑时间到达以后对订单的状态设定函数（是否达到最低价，买卖家分别是谁） -额外组件 1：建立页面查看已成交的订单 myorders.php（buyer 和 seller 皆可访问）
  - by Evan: 我觉得用不到，直接在 mybid 页面加一个 filtering -额外组件 2：在 my listing/my bid/listing 显示该物品状态（竞拍中/成交/流拍/已结束，即被其他买家拍走）
  - by Evan: 要做！！！我觉得应该直接在显示剩余时间的位置

### 3.视频展示

基本功能：

1. 用户注册：
   1. 注册失败（密码 2 次输入不一致）时弹出提示
   2. buyer 注册成功
   3. seller 注册成功
2. 用户登陆：
   1. 新注册的 buyer 登陆成功
   2. 新注册的 seller 登陆成功
3. 游客浏览网页
   1. 浏览 auction 清单
   2. 点击 auction 标题进入对应商品介绍页
   3. 浏览 commit
   4. 尝试点击 like,无法 like
   5. 尝试评论，无法评论
   6. 尝试 reply,无法 reply
4. buyer 浏览网页
   1. 登陆 buyer
   2. 浏览 auction 清单
   3. 搜索商品关键词，展示搜索结果
   4. 点击 auction 标题进入对应商品介绍页
   5. 高级搜索：
      1. 选择 category,展示对应类型的搜索结果
      2. 选择排序类型，展示对应的排序结果
         1. 按价格递增
         2. 按价格递减
         3. 按结束时间递增
         4. 按结束时间递减
      3. 选择搜索类型，展示对应搜索类型的搜索结果
         1. intersection
         2. union
5. buyer bid
   1. 进入正在 bid 的某个商品页面，输入价格，bid,展示 bid 结果
      1. 价格低于当前价格，bid 失败
      2. 价格高于当前价格，bid 成功，刷新页面展示 bid 成功结果
   2. 展示 bid history 栏目
      1. 翻页
   3. 回退到 browse 页面，多 bid 几个商品
   4. 点击 mybids,展示当前 bid 列表
   5. 进入结束 bid 的某个商品页，展示 bid 结束结果，强调无法 bid,无法 watch
   6. 进入取消 bid 的某个商品页，展示 bid 取消结果，强调无法 bid,无法 watch
6. seller auction
   1. 进入 create auction 页面，展示所有填写表单+提交 auction 功能
   2. 创建 auction 成功后，进入 my listings 页面，展示创建结果
   3. 登出，切换到 buyer 账户，进入 browse 页面，找到新创建的 auction
   4. bid 该 auction
   5. 进入 my bids,展示可以 bid 成功新创建的 auction
   6. 登出，切换回原 seller,进入 my listings,取消 auction
   7. 登出，切换到 buyer 账户，通过 my bids 进入该 auction,展示 auction 商品页面，强调 auction 已取消无法 bid
7. auction end
   1. 切换到 seller 账户，创建一个新 auction
   2. 切换到 buyer 账户，bid 该 auction
   3. 修改本地电脑系统时间到 auction 结束时间
   4. 刷新页面，展示该 auction 已结束，无法 bid
   5. TODO：如何展示 The system should confirm to both the winner and seller of an auction its outcome.功能

附加功能： 6. buyer watch 1. 登陆 buyer 账户，进入某个商品页面，尝试 watch 2. 切换到 watchlist,展示 watch 成功的结果 3. 点击该 watch 商品，进入商品页面 4. 进入邮箱，展示收到 watch 成功邮件 5. 切换到其他 buyer 账户，bid 同一件商品 6. 进入邮箱，展示收到 outbid 邮件 7. 切换回原 buyer,取消 watch 8. 切换到 watchlist,展示 watch 取消的结果 7. ==TODO buyer recommendation 8. comment 功能展示

## Commits

## Commit until 7:19, 27/11/2024 by Tim

- 修改了 send_email.php 的函数，实现向多用户发信的功能，email 和 name 可传入数组或名称，具体群发写法可参照我的 place_bid.php(如果需要的话)
- 修改了 header 和编辑信息的一些逻辑，现在登录时需要额外选择买卖家的身份，与此相对的，email 不再严格要求不重复，以在提升观感同时满足 3rd normal form 条件

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

## Commit at 21:00, 26/11/24 by Zhenghao

- 实现了忘记密码验证邮箱验证码后重置密码，并以类似的机制实现了变更密码功能（Tim：GOOD JOB，还缺了一些组件，我添加上来）

## Commit at 10:06, 26/11/24 by Evan

- 添加模拟测试数据，正在和新版的 sql_script 比对...
- 要不要新增 comments？评论区还可以添加点赞之类的功能
- browse.php 修改进行中...

## Commit at 2:30, 26/11/24 by Zhenghao

- 写了下个人信息编辑，耶！

## Commit at 23:23, 25/11/2024 by TIM

- 不装了，让我们说中文
- 几乎重构了整个数据库，添加了 buyer 和 seller 的约束表格，并将其他相关的表都从 user 外键转移到了 buyer 和 seller 上，同时 auctions 还增删了一些属性（比如 auctions 里删除了 currentPrice（想了一下不符合 3nf 就删了）以及添加了 status，方便用于筛选已结束的 auction 来发邮件）所以，请记得看我更新的 sql_script（笑）

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
  - to call that in your working php please type require_once("my_db_connect.php") at the beginning
- All changes in branch Tim.
- \*Maybe I will upload my sql script for creating tables needed in index.php tomorrow morning, stay tuned guys...(zzz)

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
