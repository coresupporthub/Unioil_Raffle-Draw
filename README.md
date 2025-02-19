
<p align="center">
  <img src="public/unioil_images/logo1.png" alt="Core Support Logo" width="100"/>
  <img src="public/unioil_images/unioil.png" alt="Unioil Logo" width="100"/>
</p>

# Unioil Raffle Promo Management System

A web-based monitoring system designed for Unioil customers' raffle promo management. This system streamlines the monitoring, reporting, and management of promo entries with user-friendly interfaces and robust backend services.

---

### Project Overview

This system facilitates the efficient management of Unioil’s raffle promotions, offering a seamless experience for both customers and administrators. Key features include:

- Customer Promo Monitoring: Track raffle entries and status.
- Administrative Dashboard: Manage promo entries and generate reports.
- Secure Authentication: Ensure authorized access using Laravel's built-in authentication.
- API Support: RESTful APIs for integrations.

---

### Tech Stack

##### Backend
- Framework: Laravel 11.32 (leveraging built-in security and performance optimizations)
- Language: PHP 8.3+
- Key Features
    - Eloquent ORM for database interactions.
    - Dependency injection and service providers for scalable architecture.
    - Artisan commands for automation and scheduling.

##### Frontend
- Technologies: Vanilla JavaScript, jQuery
- Framework: Bootstrap 5 for responsive design.
- Features:
    - AJAX-based interactions for a dynamic user experience.
    - Pre-designed components for consistent UI/UX.

##### Database
- MySQL: RDBMS for storing customer data, promo entries, and system logs.
- ORM: Laravel’s Eloquent for seamless interaction.

##### API
- Type: RESTful API.

##### Security
- Features:
    - CSRF protection.
    - SQL injection prevention via query builder and Eloquent.
    - Laravel Sanctum for API token authentication.

##### Authentication
- Type: Laravel’s built-in authentication system.
- Capabilities:
    - Multi-role user support (Admin/Customer).
    - Password encryption using bcrypt.

##### Deployment
- Cloud Platform: AWS EC2.
- Setup:
    - Nginx server for high performance.
    - App Path
        ```
        /var/www/laravel-app
        ```
    - Nginx Configuration Path
        ```
        /etc/nginx/sites-enabled
        ```
    - Cron Jobs that runs `php artisan schedule:run`
    - Supervisor that runs `php artisan queue:work`
    - Alway optimize the app after pulling changes from the repo, just run:
        ```
        php artisan optimize
        ```

##### Code Quality Tools:
- Larastan / PHPStan (Level 8 strictness).
- PHPUnit for automated testing.

### Team

##### Vendor
- Core Support Hub: Responsible for providing and maintaining technical support for this system.

##### Software Engineers
 - [@RheyanJohn15](https://github.com/RheyanJohn15) 
 - [@JpUbas](https://github.com/JpUbas) 
 - [@Tshmytzn](https://github.com/Tshmytzn) 
 - [@03hazel](https://github.com/03hazel) 

### Setup Instructions

##### Prerequisites
- PHP 8.3+
- Composer 2.8.3
- MySQL 10.4.2
- AWS CLI (for deployment)

##### Installation
1. Clone the repository:
```
git clone https://github.com/coresupporthub/Unioil_Raffle-Draw
cd Unioil_Raffle-Draw
```
2. Install dependencies:
```
composer install
```
3. Set up `.env` file
- Configure database credentials.
- Set API keys and other environment variables.
4. Run migrations and seeders:
```
php artisan migrate --seed
```
5. Start the development server:
```
php artisan serve
```

---

##### Utility Commands
Created to modify system data forcibly if necessary in the system and can only be done by devs and not by the end users

1. Reset The Whole App
    - This Custom artisan Command will delete all the files created in the system including all event_images, pdf files/zip and qr codes
    - Then Reset the db by running `php artisan migrate:fresh --seed` in the background of the command;

    Command:
    ```
    php artisan app:reset
    ```

2. Reset the Export Status
    - This Custom Artisan command will removed all the exported pdf/zip files and reset the statuses of all qr code to none export
    - This enables a fresh start in exporting the qr codes

    Command:
    ```
    php artisan app:reset-export
    ```

3. Run Larastan Code Quality Analyzer
    - Check your code quality regularly

    Command:
    ```
    ./vendor/bin/phpstan analyse
    ```

4. Run the reset user command
    - This is for forcibly updating the admin users of the system internally if its necessary
    - This is not designed to by dynamic it needs to modify the commands code to achieved the desired result
    - For devs only to avoid unauthorized update

    Command:
    ```
    php artisan app:reset-users
    ```

5. Run the add product command
    - Product does not have a management page yet in the system so for the meantime this command is created to add a product through commands

    Command:
    ```
    php artisan app:add-product --name="<required|string>" --type="<required|string>" --quantity=<required|integer|1 or 2 only>
    ```

6. Run the product List command
    - Display all the product from the database

    Command:
    ```
    php artisan app:product-list
    ```
