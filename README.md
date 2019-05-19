# Voucher Pool

A voucher pool is mock project to implement a collection of (voucher) codes that can be used by customers (recipients) to get discounts in a web shop. Each code may only be used once.

## Installation

Use the composer (https://getcomposer.org/) to install voucher_pool.

```bash
composer install

```
Create a database on mysql

```sql
CREATE DATABASE voucher_pool;

```
Change this configuration in bootstrap.php to your environment

```php
$conn = array(
    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'voucher_pool',
    'user' => 'example',
    'password' => null,
    'charset' => 'utf8mb4'
);
```
Run doctrine command to generate tables

```bash
vendor/bin/doctrine orm:schema-tool:update --force
```
## Usage

Use PHP built-in to run your server for development purposes
```bash
php -S localhost:8000 -t public
```

## Examples of routes use
Disclaimer:

Its very important to know that this project uses email in querystring params for routes. There is a bug in the php server embedded when used with parameters that have "dot" as is the case of emails.
(https://bugs.php.net/bug.php?id=61286)
For this reason when using with the php server built-in, it is necessary to add index.php to url. This bug does not happen with apache or ngix as would be the case in a production environment. The examples are as development environment.

### Create a recipient (customer)
POST http://localhost:8000/index.php/recipient
```javascript
{
    "name": "Foobar",
    "email": "foobar@teste.com"
}
```

### Find a recipient (customer)

GET http://localhost:8000/index.php/recipient/{email}

### Create a offer
When a offer is created this route also generate the vouchers to all recipients.

POST http://localhost:8000/index.php/special-offer
```javascript
{
	"name": "Oferta 54",
	"discount": 0.2,
	"expire_date": "2019-05-25"
}
```
### Use a voucher
GET http://localhost:8000/index.php/voucher/validate/code/{code}/email/{email}

### Find all available vouchers to an specific recipient email
GET http://localhost:8000/index.php/voucher/email/{email}

### Discover if a voucher has already been used
GET http://localhost:8000/index.php/voucher/{code}