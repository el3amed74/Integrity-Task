# Laravel Integrity Task API

### 1. 🗂 Repositories

 Repositories abstract the database layer. Instead of controllers/services using Eloquent models directly, they depend on interfaces. This makes your code easier to test, swap implementations, and keep business logic separated.

### 2. ⚙️ Services

 Services encapsulate business logic and transactions. They use repositories to fetch data and enforce rules (like stock reservation, order payment).

 ### 3. 🚨 Exceptions & Error Handling

Instead of manually returning responses everywhere, we throw exceptions (422, 409, etc.), and Laravel handles formatting JSON responses.

Where?

Service layer throws exceptions:

UnprocessableEntityHttpException (422)

ConflictHttpException (409)

All exceptions pass through app/Exceptions/Handler.php

### 4. 📦 RepositoryServiceProvider

The Dependency Injection Container (DIC) needs to know which implementation to use for each interface.
RepositoryServiceProvider binds contracts to implementations.


------------------------------------------------------------------------

## ⚡ 2. Quick Start

### 1. Clone & Install
```bash
git clone https://github.com/el3amed74/Integrity-Task.git
cd integrity-task
composer install
```
### 2.Run Migrations & Seeders
```bash
php artisan migrate --seed
```
### 3. API Examples
```
# to login and create token

curl -X POST http://127.0.0.1:8000/api/loign

# List Products (public)

curl -X GET http://127.0.0.1:8000/api/products


# Create Product (requires Sanctum token)

curl -X POST http://127.0.0.1:8000/api/products \
-H "Authorization: Bearer <TOKEN>" \
-H "Content-Type: application/json" \
-d '{
  "name": "Laptop Pro",
  "sku": "LP-001",
  "price": 1999.99,
  "stock": 10
}'

```
# What missing :
1- testing 
