![Mr. Prodo Banner](screenshots/1000017397.jpg)
# Mr. Prodo
Product and Sale Management System built with Laravel to help store owners manage products, purchases, sales, customers, and stock efficiently.

## Overview
Mr-Prodo is a web-based Product and Sales Management System designed for small store owners.

The system helps store owners manage their products, record purchases and sales, track stock, and maintain customer information from a single application. It reduces the need for manual transaction books and calculations by keeping product and transaction data organized digitally.

The application follows a structured Laravel architecture with controllers, Form Requests, policies, services, models, and API resources to keep responsibilities separated and the codebase maintainable.

## Features

### Authentication & Authorization

* User registration and login
* Profile management
* Session-based authentication
* Policy-based authorization

### Product Management

* Create, update, and delete products
* Category management
* Manage product unit, stock, purchase price, and selling price
* Supported units including pcs, kg, g, meter, cm, ltr, and ml
* Product unit determines the unit used when purchasing and selling the product
* Store the last purchase and selling price for automatic price autofilling during transactions
* Soft deletion of products
* Paginated product listing

### Purchase Management

* Create purchases with multiple purchase items
* Select products and enter quantities and prices for each purchase item
* Automatically increase product stock according to purchased quantities
* Automatically update the product's purchase price with the latest purchase price
* Purchase and purchase items are processed within a database transaction
* Eloquent relationships between purchases, purchase items, and products

### Sales Management

* Create sales with multiple sale items
* Support both registered customers and walk-in customers
* Select products and enter quantities and prices for each sale
* Validate stock availability before completing a sale
* Throw a custom `InsufficientStockException` when available stock is less than the requested sale quantity
* Automatically deduct product stock according to sold quantities
* Automatically update the product's selling price with the latest selling price
* Sale and sale items are processed within a database transaction
* Roll back the transaction when an insufficient-stock exception occurs
* Eloquent relationships between sales, sale items, and products

### Customer Management

* Add and manage customer information
* Track customer sales
* Display total sales amount for each customer
* Paginated customer listing

### Validation & Error Handling

* Server-side validation using Form Requests
* Policy-based authorization for protected operations
* Custom insufficient-stock exception handling
* JSON error responses for API requests
* Database transactions for data consistency

### Application Architecture

* Service-layer business logic
* Eloquent relationships and model scopes
* API Resources for structured JSON responses
* Dependency Injection through Laravel's Service Container
* Separation of controllers, validation, authorization, business logic, and data transformation
