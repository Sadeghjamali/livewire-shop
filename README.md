# Laravel 11 E-commerce Application

A practice e-commerce application built with Laravel 11, Livewire 3, Alpine.js, Tailwind CSS, and the Zarinpal payment gateway.

## Features

- Product listing and product detail pages
- Server-side shopping cart interactions using Livewire
- Client-side interactions using Alpine.js
- Zarinpal payment gateway integration
- Payment request, callback handling, and transaction verification
- Payment logic organized into dedicated Service and Action classes

## Tech Stack

- PHP 8.2+
- Laravel 11
- Livewire 3
- Alpine.js
- Tailwind CSS
- MySQL or another supported Laravel database
- Redis for features that use Redis
- Zarinpal payment gateway

## Requirements

- PHP 8.2 or later
- Composer
- Node.js and npm
- A supported database
- Redis, if using Redis-dependent functionality

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/Sadeghjamali/livewire-shop.git
   cd livewire-shop
   ```

2. Install PHP dependencies:

   ```bash
   composer install
   ```

3. Create your local environment file:

   ```bash
   cp .env.example .env
   ```

4. Generate the application key:

   ```bash
   php artisan key:generate
   ```

5. Configure your database, Redis connection, and payment gateway settings in `.env`. Never commit real credentials or API keys.

6. Run database migrations:

   ```bash
   php artisan migrate
   ```

7. Install frontend dependencies and build assets:

   ```bash
   npm install
   npm run build
   ```

8. Start the development server:

   ```bash
   php artisan serve
   ```

## Payment Integration

The application integrates with Zarinpal for payment requests and callback processing. The payment flow includes returning from the gateway and verifying the transaction result.

Use your own test credentials and consult the gateway documentation before testing payments.

## Project Structure

Payment-related responsibilities are separated into Service and Action classes to keep the payment flow organized.

## Notes

This is a learning and portfolio project. Review configuration, error handling, and security settings before using it in production.

## Repository

https://github.com/Sadeghjamali/livewire-shop
