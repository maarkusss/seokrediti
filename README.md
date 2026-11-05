# Eirokrediti.lv

## Installation

Install Composer dependencies

```bash
$ composer install
```

Create a copy of the .env.example file

```bash
$ cp .env.example .env
```

Generate an app encryption key

```bash
$ php artisan key:generate
```

Create a storage folder link in /public/ folder

```bash
$ php artisan storage:link
```

Fill .env file with your database settings and migrate the database

```bash
$ php artisan migrate
```

After registering the first user, add admin permissions to it

```bash
$ php artisan permission:give
```

Install NPM dependencies if needed

```bash
$ npm install
```

## Usage (local)

```bash
$ php artisan serve
```
