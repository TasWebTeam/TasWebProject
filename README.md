<div align="center">
  <img width="400" height="400" alt="image" src="https://github.com/user-attachments/assets/749512cd-402c-44ac-93ef-b14a5813ca46" />

  # Te Acerco Salud (TAS)
</div>

![Laravel](https://img.shields.io/badge/Laravel-Backend-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-Language-777BB4?logo=php&logoColor=white)
![Blade](https://img.shields.io/badge/Blade-Template%20Engine-F05340)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-Database-4169E1?logo=postgresql&logoColor=white)
![REST API](https://img.shields.io/badge/API-RESTful-009688)
![Flutter](https://img.shields.io/badge/Flutter-Mobile-02569B?logo=flutter&logoColor=white)
![Platform](https://img.shields.io/badge/Platform-Android%20%7C%20iOS-white)
![Architecture](https://img.shields.io/badge/Architecture-MVC-purple)
![Methodology](https://img.shields.io/badge/Methodology-SCRUM%20%7C%20UP-blue)
![UML](https://img.shields.io/badge/Modeling-UML%20%26%20UWE-orange)
![Enterprise Architect](https://img.shields.io/badge/CASE-Enterprise%20Architect-red)
![Security](https://img.shields.io/badge/Security-Sanctum%20%7C%20CSRF-green)
![Status](https://img.shields.io/badge/Status-Completed-brightgreen)

## Overview

**Te Acerco Salud** is a web and mobile platform that connects patients with a collaborative pharmacy network to streamline prescription fulfillment. Users can upload prescriptions, select their preferred pharmacy, and ensure complete medication availability without visiting multiple locations.

## Problem Statement

Patients often cannot fill complete prescriptions at a single pharmacy, leading to:
- Multiple pharmacy visits and increased transportation costs
- Treatment delays
- Health risks for critical patients
- Poor user experience due to fragmented inventory

## Solution

A collaborative system between pharmacy chains that enables:
- Digital prescription submission
- Automatic medication availability verification
- Cross-pharmacy cooperation for complete fulfillment
- Optimized delivery and pickup times

## Tech Stack

- **Backend**: Laravel (PHP) - MVC Architecture
- **Frontend**: Laravel Blade
- **Mobile**: Android Studio (Kotlin/Java), Flutter (Dart)
- **Database**: MySQL/PostgreSQL
- **API**: RESTful Web Services
- **Methodology**: Unified Process (UP) & SCRUM
- **Modeling**: UML & UWE notation
- **CASE Tool**: Enterprise Architect

## Key Features

### Patients
- User registration and profile management
- Enter prescriptions manually (text input)
- Pharmacy and branch selection
- Medication availability verification
- Order tracking and notifications

### Pharmacies
- Branch registration in collaborative network
- Real-time inventory management
- Order processing
- Cross-branch cooperation system

## Installation

```bash
# Clone repository
git clone https://github.com/TasWebTeam/TasWebProject.git
cd te-acerco-salud

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_DATABASE=tas_db
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Compile assets
npm run dev

# Start server
php artisan serve
```

## Security

- Authentication: Laravel Sanctum/Passport
- Password encryption: bcrypt
- CSRF protection
- API rate limiting
- Input validation and sanitization

## User Roles

1. **Patient** - Upload prescriptions and place orders
2. **Pharmacy Employee** - Process orders
   
## Development Methodology

**SCRUM**
- 2-week sprints
- Daily standups
- Sprint planning, review, and retrospectives

**Unified Process**
- Inception, Elaboration, Construction, Transition phases

## Project Structure

```
te-acerco-salud/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   └── Services/
├── database/migrations/
├── resources/views/
├── routes/
│   ├── web.php
│   └── api.php
├── tests/
└── docs/models/
```

## Team

This project was developed as a team effort by Computer Systems Engineering students.
