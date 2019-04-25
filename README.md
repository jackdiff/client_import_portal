# CIP

This is a basic tool allow import customer data from excel file to database.
It's features include : create Client Category, import excel file, filter customer data.
# Tech stack

- MySQL
- Nginx
- Laravel
- Reactjs
- Build development env by Docker
- Bundle resource by Webpack


# Installation

## Requirement
- PHP 7.2
- MySQL 5.7.23
- PHP extension php_zip enabled
- PHP extension php_xml enabled
- PHP extension php_gd2 enabled
## Clone project
- `git clone git@github.com:jackdiff/client_import_portal.git`
- `cd client_import_portal`
- `git checkout v1.0`
## Build docker workspace 

This project is developed by using docker to create workspace. If you want to use docker, you need install docker and docker composer on your machine : [https://docs.docker.com/install/](https://docs.docker.com/install/)
After docker and docker composer installed, In project root directory, run commands : 
- `cd dockers/`
- `docker-compose up -d --build nginx php-fpm mysql workspace`

## Install dependencies && create database
In project  root directory, run commands : 
- `cd dockers/`
- `docker-compose exec workspace composer install`
- `docker-compose exec workspace php artisan migrate`


## Add host name

- Open hosts file (*nix os  : /etc/hosts)
- Add : `127.0.0.1   cip.test`

## Make env file
- In project root directory: Rename file .env_test => .env  `cp .env_test .env`
- `cd dockers`
- In dockers directory : Rename file .env_test => .env `cp .env_test .env`
## Additional setting

If you do not use docker, you may need some additional settings :
- Give corresponding permission on folder : `storage`
- Key in your database credential in .env file :
  - `DB_HOST`
  - `DB_PORT`
  - `DB_DATABASE`
  - `DB_USERNAME`
  - `DB_PASSWORD`

## TESTING
- Visit http://cip.test:8088 to see the result.
# USER GUIDE

1. Add category
- Visit http://cip.test:8088/category
- Click button Add
- Key in category name
- Save

2. Import excel file
- Visit http://cip.test:8088/import
- Choose a category
- Select a excel file (Can use excel file for test under folder resources/test/kh.xls)
- Select customer's field that corresponding to the column in excel file.
- Finish import
3. Filter customers
- Visit http://cip.test:8088
- It shows all customer imported by default
- Select category, key in name, address, tel to search customers