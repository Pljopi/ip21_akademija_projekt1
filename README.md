# Crypto price app and webstie

---
Simple CLI app and website
This is a learning project in a unfinished stage, it is still being updated.

---

## Set up

### Install docker and docker compose.

### Build docker:

    docker-compose up -d

#### Enter container:
 
     php -  docker-compose exec php_app bash
     mysql - docker-compose exec php_app_db bash

### Build dependencies:

    install composer

    install twig

## MySql database and tables
Fill your .env file with your server data
### Database : crypto


* Table structure for table `Favourites`
CREATE TABLE `Favourites` (
  `tag` char(20) NOT NULL,
  `id` int NOT NULL,
  `users_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

* Table structure for table `IPadress`

CREATE TABLE `IPadress` (
  `ip_adress` varchar(20) NOT NULL,
  `TIME` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

* Table structure for table `users`
CREATE TABLE `users` (
  `users_id` int NOT NULL,
  `users_uid` tinytext NOT NULL,
  `users_pwd` longtext NOT NULL,
  `users_email` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

---------------------------
##  CLI app

* For exstensive  instructions on how to use the CLI app, refer to user_stories.md
----------------------
##  WEBSITE

* Basic website, user can signup, login and add/remove currencies from his/her list of favourites and check currency prices.
* User is limited to 3 failed log-in attempts from a single IP-address/h.
-----------------------
## Built on

* PHP : '8'
* twig : '2.15.1'.
* mysql : 8
* nginx: alpine


