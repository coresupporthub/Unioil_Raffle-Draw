
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
git clone https://github.com/Tshmytzn/Unioil_Raffle-Draw
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
