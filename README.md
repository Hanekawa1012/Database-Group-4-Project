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