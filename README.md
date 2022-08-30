# Crypto price app and webstie

---
Simple CLI app and website
This is a learning project in a unfinished stage, it is still being updated.

---

## Set up

Install docker and docker compose

Install composer and twig

Install twig

Build on:

* PHP : '8'
* twig : '2.15.1'.
* phpmyadmin : 5
* mysql : 8
* nginx: alpine

Fill your .env file with your server data

---

## * CLI app

* For exstensive  instructions on how to use the CLI app, refer to user_stories.md

---

## * WEBSITE

* Basic website, user can signup, login and add/remove currencies from his/her lsit of favourites and check currency prices.
* User is limited to 3 failed log-in attempts from a single IP-address/h.

---

## MySql database and tables

### * Database : crypto

* table "favourites":
"tag", "id", "users_id" 

//both "tag" and "users_id" are unique
* table "IPadress":
"ip_adress" "TIME"
* table "users":
"users_id", "users_uid", "users_pwd", "users_email"  

// "users_id" is unique


