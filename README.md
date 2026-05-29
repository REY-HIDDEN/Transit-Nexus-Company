# TransitNexus - Bus Transit Booking System

> A modern, full-featured bus transit booking platform built with Laravel 12, enabling seamless bus ticket reservations, trip management, and passenger tracking.

## Table of Contents

- [Overview](#overview)
- [Technology Stack](#technology-stack)
- [Features](#features)
- [Installation & Setup](#installation--setup)
- [System Architecture](#system-architecture)
- [User Roles & Workflows](#user-roles--workflows)
- [Database Schema](#database-schema)
- [API Routes & Endpoints](#api-routes--endpoints)
- [Controllers Overview](#controllers-overview)
- [Models & Relationships](#models--relationships)
- [Configuration](#configuration)
- [Usage Guide](#usage-guide)
- [Development Guide](#development-guide)
- [Troubleshooting](#troubleshooting)

---

## Overview

**TransitNexus** is a comprehensive bus transit booking management system designed to streamline the process of:
- Managing bus fleets and their operations
- Creating and scheduling routes and trips
- Handling passenger bookings with automatic seat allocation
- Processing payments and booking confirmations
- Managing user accounts with role-based access control
- Supporting multiple languages (English & Kinyarwanda)

The system is built on Laravel 12 with a responsive Bootstrap 5 interface, ensuring smooth operations for both administrators and customers.

---

## Technology Stack

| Component | Technology |
|-----------|-----------|
| **Framework** | Laravel 12 |
| **Language** | PHP 8.2+ |
| **Database** | SQLite / MySQL (configurable) |
| **Frontend** | Blade Templating, Bootstrap 5, Bootstrap Icons |
| **Styling** | Tailwind CSS 4.0 (via Vite) |
| **Build Tool** | Vite |
| **Package Manager** | Composer, npm |
| **Authentication** | Laravel Session-based |
| **Email** | Laravel Mail System (Mailable classes) |
| **Job Queue** | Laravel Queue System |

---

## Features

### 🔐 User Management & Authentication
- **Three User Roles**: Admin, Customer, Guest
- Login & registration with password hashing
- Forgotten password recovery with email links
- Session-based authentication with CSRF protection
- User profile management (name, email, phone, avatar)
- Password change functionality

### 🚌 Fleet Management (Admin)
- Create, update, and manage bus inventory
- Track bus status (active, maintenance, retired)
- Monitor bus capacity and driver information
- Agency/carrier tracking
- Automatic trip count display
- Advanced search and filtering

### 🗺️ Route Management (Admin)
- Define transportation routes (origin → destination)
- Set ticket prices per route
- Record distance information
- Automatic trip count tracking
- Comprehensive route listing with search

### 🛫 Trip Scheduling (Admin)
- Schedule trips linking buses and routes
- Set departure and arrival times
- Manage trip status (scheduled, boarding, completed)
- Real-time capacity tracking
- Validate bus availability and capacity constraints
- Automatic seat allocation validation

### 🎫 Booking System
- **Customer Booking**:
  - Browse available trips on home page
  - Search trips by origin and destination
  - Create bookings with seat selection
  - Auto-generated ticket numbers
  - Phone number validation
  - Pending approval workflow
  
- **Admin Booking Management**:
  - View all bookings with advanced search
  - Approve/reject bookings
  - Email notifications on approval
  - Payment status tracking (paid/pending)
  - Booking status tracking (confirmed/pending)
  - Automatic seat allocation with duplicate prevention

### 🌍 Internationalization
- Support for English (en) and Kinyarwanda (rw)
- Language switcher in navigation
- Session-based locale persistence
- Translated email notifications

### 📊 Admin Dashboard
- Key metrics: total buses, routes, trips, confirmed bookings
- Revenue tracking from paid bookings
- Recent bookings list (latest 8)
- Upcoming trips list (next 6)
- Visual metric cards with icons
- Real-time data aggregation

---

## Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- SQLite or MySQL database

### Step 1: Clone & Install Dependencies

```bash
# Navigate to project directory
cd TransitNexus

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### Step 2: Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file
# For SQLite (default):
# DB_CONNECTION=sqlite
# DB_DATABASE=/full/path/to/database.sqlite

# For MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=transitnexus
# DB_USERNAME=root
# DB_PASSWORD=
```

### Step 3: Database Setup

```bash
# Run migrations
php artisan migrate

# (Optional) Seed database with sample data
php artisan db:seed
```

### Step 4: Frontend Assets

```bash
# Build frontend assets with Vite
npm run build

# Or for development with hot reload
npm run dev
```

### Step 5: Start the Application

```bash
# Start Laravel development server
php artisan serve
```

The application will be available at `http://localhost:8000`

### Quick Setup (Automated)

If using the setup script in `composer.json`:
```bash
composer run setup
```

---

## System Architecture

### High-Level Flow

```
Public User
    ↓
Home Page (Browse Trips)
    ↓
Login/Register
    ↓
Customer Dashboard
    ↓
Book Trip → Pending Booking
    ↓
Admin Dashboard → Approve Booking → Email Notification → Confirmed Booking
    ↓
Customer Views Confirmed Ticket
```

### Component Architecture

```
Routes (web.php)
    ↓
Controllers (HTTP Request Handlers)
    ↓
Models (Eloquent ORM - Database Layer)
    ↓
Database (SQLite/MySQL)

Middleware
    ├── EnsureUserIsAdmin
    ├── SetLocale
    └── auth (Laravel Built-in)

Views (Blade Templates)
    └── Bootstrap 5 + Tailwind CSS (Responsive UI)
```

---

## User Roles & Workflows

### 👤 Guest User
**Permissions:**
- View home page
- Search available trips
- Access login/register pages

**Cannot:**
- Book tickets
- View personal dashboard
- Access admin panel

### 👨‍💼 Customer User
**Permissions:**
- Everything a guest can do
- Create bookings
- View personal dashboard (account, bookings, profile)
- Edit profile (name, email, phone, avatar)
- Change password

**Workflow:**
1. Register account → Email verification (optional)
2. Browse available trips on home page or search
3. Click "Book" on desired trip
4. Fill booking form (passenger name, phone, seat)
5. Submit booking (enters pending state)
6. Wait for admin approval via email notification
7. View confirmed booking in account dashboard

### 🛡️ Admin User
**Permissions:**
- Everything customers can do
- Full fleet management (CRUD buses)
- Full route management (CRUD routes)
- Full trip scheduling (CRUD trips)
- Full booking management (view, approve, reject)
- Admin dashboard with analytics
- User role assignment

**Workflow:**
1. Login with admin account
2. Access admin dashboard via `/admin`
3. Manage buses, routes, trips via sidebar navigation
4. Review pending bookings
5. Approve bookings (triggers email to customer)
6. Monitor metrics and revenue
7. View recent activity

---

## Database Schema

### Users Table
```sql
id, name, email, password, email_verified_at, 
role (admin/customer), remember_token, 
phone, avatar, created_at, updated_at
```
**Primary Key:** id | **Indexes:** email, role

### Buses Table
```sql
bus_id (PK), plate_number (UNIQUE), capacity, 
driver_name, agency, status (active/maintenance/retired), 
created_at, updated_at
```
**Relationships:** 
- hasMany → Trips
- hasMany → Bookings (through Trips)

### Routes Table
```sql
route_id (PK), origin, destination, distance, 
ticket_price, created_at, updated_at
```
**Relationships:**
- hasMany → Trips
- hasMany → Bookings (through Trips)

### Trips Table
```sql
trip_id (PK), bus_id (FK), route_id (FK), 
departure_date, departure_time, arrival_time, 
status (scheduled/boarding/completed), 
created_at, updated_at
```
**Relationships:**
- belongsTo → Bus
- belongsTo → Route
- hasMany → Bookings

**Indexes:** bus_id, route_id, departure_date (for performance)

**Methods:**
- `confirmedBookings()` - Get all confirmed bookings
- `reservedBookings()` - Get all reserved/pending bookings
- `remainingSeats()` - Calculate available seat count

### Bookings Table
```sql
booking_id (PK), user_id (FK), trip_id (FK), 
passenger_name, phone_number, seat_number, 
booking_date, payment_status (paid/pending), 
booking_status (confirmed/pending), 
ticket_number (UNIQUE), created_at, updated_at
```
**Relationships:**
- belongsTo → User
- belongsTo → Trip

**Constraints:**
- Unique: ticket_number
- Unique: (trip_id, seat_number) - no duplicate seats per trip

**Methods:**
- `generateTicketNumber()` - Generate unique ticket
- `scopePaid()` - Query paid bookings

---

## API Routes & Endpoints

### Public Routes
| Method | Route | Controller | Purpose |
|--------|-------|-----------|---------|
| GET | `/` | HomeController@index | Browse upcoming trips, search |
| GET | `/login` | AuthController@showLogin | Show login form |
| POST | `/login` | AuthController@login | Process login |
| GET | `/register` | AuthController@showRegister | Show registration form |
| POST | `/register` | AuthController@register | Process registration |
| GET | `/forgot-password` | AuthController@showForgotPasswordForm | Password recovery form |
| POST | `/forgot-password` | AuthController@sendResetLinkEmail | Send reset email |
| GET | `/reset-password/{token}` | AuthController@showResetForm | Show reset form |
| POST | `/reset-password` | AuthController@resetPassword | Process password reset |
| GET | `/lang/{locale}` | Language Switcher | Toggle language (en/rw) |

### Authenticated Routes (Customer)
| Method | Route | Controller | Purpose |
|--------|-------|-----------|---------|
| POST | `/logout` | AuthController@logout | Logout user |
| GET | `/account` | CustomerDashboardController@index | View profile & bookings |
| POST | `/account/profile` | CustomerDashboardController@updateProfile | Update profile info |
| POST | `/account/avatar` | CustomerDashboardController@updateAvatar | Upload profile picture |
| POST | `/account/password` | CustomerDashboardController@updatePassword | Change password |
| GET | `/trips/{trip}/book` | CustomerBookingController@create | Show booking form |
| POST | `/trips/{trip}/book` | CustomerBookingController@store | Create booking |

### Admin Routes (Auth + Admin Middleware)
| Method | Route | Controller | Purpose |
|--------|-------|-----------|---------|
| GET | `/admin` | DashboardController@index | Admin dashboard |
| GET | `/admin/buses` | BusController@index | List buses |
| GET | `/admin/buses/create` | BusController@create | Create bus form |
| POST | `/admin/buses` | BusController@store | Store bus |
| GET | `/admin/buses/{bus}/edit` | BusController@edit | Edit bus form |
| PUT | `/admin/buses/{bus}` | BusController@update | Update bus |
| DELETE | `/admin/buses/{bus}` | BusController@destroy | Delete bus |
| GET | `/admin/routes` | RouteController@index | List routes |
| GET | `/admin/routes/create` | RouteController@create | Create route form |
| POST | `/admin/routes` | RouteController@store | Store route |
| GET | `/admin/routes/{route}/edit` | RouteController@edit | Edit route form |
| PUT | `/admin/routes/{route}` | RouteController@update | Update route |
| DELETE | `/admin/routes/{route}` | RouteController@destroy | Delete route |
| GET | `/admin/trips` | TripController@index | List trips |
| GET | `/admin/trips/create` | TripController@create | Create trip form |
| POST | `/admin/trips` | TripController@store | Store trip |
| GET | `/admin/trips/{trip}/edit` | TripController@edit | Edit trip form |
| PUT | `/admin/trips/{trip}` | TripController@update | Update trip |
| DELETE | `/admin/trips/{trip}` | TripController@destroy | Delete trip |
| GET | `/admin/bookings` | BookingController@index | List bookings |
| GET | `/admin/bookings/create` | BookingController@create | Create booking form |
| POST | `/admin/bookings` | BookingController@store | Store booking |
| GET | `/admin/bookings/{booking}/edit` | BookingController@edit | Edit booking form |
| PUT | `/admin/bookings/{booking}` | BookingController@update | Update booking |
| DELETE | `/admin/bookings/{booking}` | BookingController@destroy | Delete booking |
| POST | `/admin/bookings/{booking}/approve` | BookingController@approve | Approve booking & send email |

---

## Controllers Overview

### AuthController
**Methods:**
- `showLogin()` - Display login form
- `login()` - Authenticate user with email & password
- `showRegister()` - Display registration form
- `register()` - Create new user account (defaults to 'customer' role)
- `logout()` - End user session
- `showForgotPasswordForm()` - Show password recovery form
- `sendResetLinkEmail()` - Email password reset link
- `showResetForm()` - Display password reset form
- `resetPassword()` - Update password with reset token

**Security:**
- Password hashing with bcrypt
- Session CSRF protection
- Guest middleware (prevents logged-in users from accessing)

### DashboardController (Admin)
**Purpose:** Provide admin with system overview and metrics

**Methods:**
- `index()` - Display dashboard with:
  - Total buses count
  - Total routes count
  - Total trips count
  - Confirmed bookings count
  - Revenue (sum of paid bookings)
  - Recent bookings (latest 8)
  - Upcoming trips (next 6)

### BusController
**Actions:** CRUD operations for bus fleet management

**Methods:**
- `index()` - List buses with search, pagination
- `create()` - Show create bus form
- `store()` - Save new bus
- `edit()` - Show edit bus form
- `update()` - Update bus information
- `destroy()` - Delete bus and cascade trips/bookings

**Features:**
- Search by plate number, driver name, agency
- Status filter (active/maintenance/retired)
- Trip count per bus display
- Validation: unique plate_number, required fields

### RouteController
**Actions:** CRUD for transportation routes

**Methods:**
- `index()` - List routes with search
- `create()` - Show create route form
- `store()` - Save new route
- `edit()` - Show edit route form
- `update()` - Update route
- `destroy()` - Delete route (cascade trips)

**Features:**
- Search by origin/destination
- Trip count display
- Price and distance tracking

### TripController
**Actions:** Manage trip scheduling

**Methods:**
- `index()` - List trips with search/filter
- `create()` - Show create trip form with bus/route dropdowns
- `store()` - Schedule trip with validations
- `edit()` - Show edit trip form
- `update()` - Update trip details
- `destroy()` - Delete trip (cascade bookings)

**Validations:**
- Bus capacity vs booking count
- Bus not already booked at same time
- Departure before arrival times
- Valid bus and route selection

### BookingController (Admin)
**Actions:** Full admin booking management

**Methods:**
- `index()` - List bookings with advanced search
- `create()` - Show manual booking creation form
- `store()` - Store booking
- `edit()` - Show edit booking form
- `update()` - Update booking
- `destroy()` - Delete booking (frees seat)
- `approve()` - Approve booking & send email

**Advanced Search:**
- Passenger name
- Phone number
- Ticket number
- Bus plate number
- Route (origin/destination)

**Seat Allocation:**
- Validate no duplicate seats per trip
- Atomic seat assignment
- Prevent overbooking

**Email Notifications:**
- Triggered on approval only
- Uses BookingApproved Mailable
- Queued for async delivery

### CustomerBookingController
**Purpose:** Simplified booking flow for customers

**Methods:**
- `create()` - Show booking form with trip details
- `store()` - Create booking with:
  - Auto-generated ticket number
  - Default pending status
  - User ID auto-populated
  - Seat availability validation

**Workflow:**
1. Customer selects trip
2. Fills passenger details (name, phone)
3. Selects seat (if manual) or auto-assigned
4. Booking saved as "pending"
5. Awaits admin approval

### HomeController
**Purpose:** Public trip browsing and search

**Methods:**
- `index()` - Display:
  - Upcoming trips (next 8, from today forward)
  - Trip search form (origin/destination)
  - Trip cards with status, price, capacity info

### CustomerDashboardController
**Purpose:** Customer account management

**Methods:**
- `index()` - Show:
  - User profile info
  - Booking history
  - Profile edit form
  - Avatar upload form
  - Password change form
- `updateProfile()` - Update name, email, phone
- `updateAvatar()` - Upload and store user avatar
- `updatePassword()` - Change password with validation

---

## Models & Relationships

### User Model
```php
<?php
// Relationships
- hasMany('bookings') // User → Booking (one-to-many)

// Key Attributes
- id, name, email, password, role, phone, avatar
- email_verified_at, remember_token, timestamps

// Methods
- scopeAdmin() // Query only admin users
- scopeCustomer() // Query only customer users
```

### Bus Model
```php
<?php
// Relationships
- hasMany('trips') // Bus → Trip (one-to-many)
- hasManyThrough('bookings', 'trips') // Bus → Booking (through Trip)

// Key Attributes
- bus_id (PK), plate_number (UNIQUE), capacity, driver_name, agency
- status (active/maintenance/retired), timestamps

// Methods
- scopeActive() // Filter active buses
- tripsCount() // Get associated trips count
```

### Route Model
```php
<?php
// Relationships
- hasMany('trips') // Route → Trip (one-to-many)
- hasManyThrough('bookings', 'trips') // Route → Booking (through Trip)

// Key Attributes
- route_id (PK), origin, destination, distance, ticket_price, timestamps

// Computed Properties
- name // Returns "origin to destination"

// Methods
- scopeByDestination($origin, $destination) // Query by locations
```

### Trip Model
```php
<?php
// Relationships
- belongsTo('bus') // Trip → Bus (many-to-one)
- belongsTo('route') // Trip → Route (many-to-one)
- hasMany('bookings') // Trip → Booking (one-to-many)

// Key Attributes
- trip_id (PK), bus_id (FK), route_id (FK)
- departure_date, departure_time, arrival_time
- status (scheduled/boarding/completed), timestamps

// Methods
- confirmedBookings() // Get confirmed bookings count
- reservedBookings() // Get pending bookings count
- remainingSeats() // Calculate available seats
- scopeUpcoming() // Filter future trips
```

### Booking Model
```php
<?php
// Relationships
- belongsTo('user') // Booking → User (many-to-one)
- belongsTo('trip') // Booking → Trip (many-to-one)

// Key Attributes
- booking_id (PK), user_id (FK), trip_id (FK)
- passenger_name, phone_number, seat_number
- booking_date, payment_status, booking_status
- ticket_number (UNIQUE), timestamps

// Methods
- generateTicketNumber() // Create unique ticket identifier
- scopePaid() // Query paid bookings
- scopeConfirmed() // Query confirmed bookings
```

---

## Configuration

### Environment Variables (.env)

```env
# Application
APP_NAME="TransitNexus"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite
# OR for MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=transitnexus
# DB_USERNAME=root
# DB_PASSWORD=

# Mail
MAIL_MAILER=log
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@transitnexus.local
MAIL_FROM_NAME="TransitNexus"

# Queue
QUEUE_CONNECTION=sync

# Session
SESSION_LIFETIME=120
SESSION_DRIVER=file

# Cache
CACHE_STORE=file

# Localization
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
```

### Configuration Files

**config/app.php** - Application name, timezone, locale
**config/auth.php** - Authentication guards and providers
**config/database.php** - Database connection settings
**config/mail.php** - Email service configuration
**config/queue.php** - Queue driver settings
**config/session.php** - Session management settings

---

## Usage Guide

### For Customers

#### 1. Register Account
1. Go to homepage
2. Click "Register"
3. Enter name, email, password
4. Submit form
5. Account created as 'customer' role

#### 2. Browse & Search Trips
1. On homepage, view upcoming trips
2. Use search form: enter origin (departure city) and destination (arrival city)
3. View filtered results with price, capacity, times
4. Click "Book" button on desired trip

#### 3. Complete Booking
1. Fill passenger information:
   - Passenger name (must match ID)
   - Phone number (validation applied)
   - Select seat number
2. Review trip details
3. Click "Confirm Booking"
4. Booking enters 'pending' state
5. Wait for admin approval via email

#### 4. Manage Profile
1. Click "Account" in navigation
2. Update profile:
   - Name, email, phone
   - Upload/change avatar
3. Change password
4. View booking history

#### 5. View Bookings
1. Go to "Account" dashboard
2. See all bookings with status
3. View ticket numbers for confirmed bookings
4. Contact admin if issues

### For Administrators

#### 1. Login to Admin Panel
1. Go to `/login`
2. Enter admin credentials
3. Dashboard auto-loads
4. Use sidebar for navigation

#### 2. Manage Buses
1. Click "Buses" in sidebar
2. View all buses with details:
   - Plate number, capacity, driver, agency
   - Current status
   - Associated trips count
3. **Add Bus**:
   - Click "Create Bus" button
   - Fill form: plate, capacity, driver, agency, status
   - Submit
4. **Edit Bus**:
   - Click pencil icon on bus row
   - Update information
   - Save changes
5. **Delete Bus**:
   - Click trash icon
   - Confirm deletion (cascades to trips and bookings)

#### 3. Manage Routes
1. Click "Routes" in sidebar
2. View all routes (origin → destination)
3. **Add Route**:
   - Click "Create Route"
   - Enter origin city, destination city
   - Set distance (km)
   - Set ticket price
   - Submit
4. **Edit/Delete**: Similar to buses

#### 4. Schedule Trips
1. Click "Trips" in sidebar
2. View scheduled trips
3. **Add Trip**:
   - Click "Create Trip"
   - Select bus (dropdown)
   - Select route (dropdown)
   - Set departure date
   - Set departure time
   - Set arrival time
   - Select status (scheduled/boarding)
   - Submit
4. System validates:
   - Bus not double-booked
   - Bus capacity sufficient
   - Departure before arrival
5. **Monitor Trip**:
   - View current bookings count
   - See remaining seats
   - Change status as needed

#### 5. Manage Bookings
1. Click "Bookings" in sidebar
2. View all bookings with search options:
   - Passenger name
   - Phone number
   - Ticket number
   - Bus plate
   - Route
3. **Approve Booking**:
   - Find pending booking
   - Click "Approve" button
   - Email sent to customer
   - Booking status changes to 'confirmed'
4. **View/Edit**:
   - Click booking row for details
   - Edit booking information if needed
   - Update payment status
5. **Delete Booking**:
   - Click trash icon
   - Seat becomes available

#### 6. View Dashboard
1. Go to `/admin`
2. See key metrics:
   - Total buses, routes, trips
   - Confirmed bookings count
   - Revenue from paid bookings
3. View recent bookings (latest 8)
4. View upcoming trips (next 6)
5. Use data for decision making

#### 7. Handle Multiple Languages
1. Click language switcher (top right)
2. Select English or Kinyarwanda
3. Interface updates
4. Emails sent in selected language

---

## Development Guide

### Project Structure
```
TransitNexus/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Request handlers
│   │   ├── Middleware/           # Custom middleware
│   │   └── Requests/             # Form validation
│   ├── Mail/                     # Mailable classes
│   ├── Models/                   # Eloquent models
│   └── Providers/                # Service providers
├── bootstrap/                    # Application initialization
├── config/                       # Configuration files
├── database/
│   ├── factories/                # Model factories for testing
│   ├── migrations/               # Schema definitions
│   └── seeders/                  # Database seeders
├── public/                       # Web root, assets
├── resources/
│   ├── css/                      # Stylesheets
│   ├── js/                       # JavaScript
│   └── views/                    # Blade templates
├── routes/
│   ├── web.php                   # Web routes
│   └── console.php               # Console commands
├── storage/                      # File uploads, logs
├── tests/                        # Unit & feature tests
├── vendor/                       # Dependencies
├── artisan                       # CLI tool
├── composer.json                 # PHP dependencies
├── package.json                  # Node dependencies
└── vite.config.js               # Vite configuration
```

### Adding a New Feature

**Example: Add "Email Notification on Booking" Feature**

1. **Create Mailable Class**:
```bash
php artisan make:mail BookingNotification
```

2. **Define Mailable**:
```php
// app/Mail/BookingNotification.php
public function envelope(): Envelope
{
    return new Envelope(subject: 'Your Booking Confirmation');
}
```

3. **Update Controller**:
```php
// app/Http/Controllers/BookingController.php
use App\Mail\BookingNotification;
use Illuminate\Support\Facades\Mail;

public function approve(Booking $booking)
{
    // ... approval logic ...
    Mail::to($booking->user->email)->send(new BookingNotification($booking));
}
```

4. **Create Blade Template**:
```blade
<!-- resources/views/emails/booking-notification.blade.php -->
...email content...
```

5. **Test Locally**:
```bash
# Test mail in log
# Check storage/logs/laravel.log
```

### Adding a New Controller

```bash
# Generate controller with resource methods
php artisan make:controller YourResourceController --resource

# Generate controller with model binding
php artisan make:controller YourController -m YourModel
```

### Adding a New Model

```bash
# Generate model with migration
php artisan make:model YourModel -m

# Generate model with factory and seeder
php artisan make:model YourModel -m -f -s
```

### Running Migrations

```bash
# Run all pending migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Reset database (rollback all)
php artisan migrate:reset

# Refresh (reset + migrate)
php artisan migrate:refresh

# Refresh with seeding
php artisan migrate:refresh --seed
```

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/BookingTest.php

# Run with coverage
php artisan test --coverage
```

### Debugging

```bash
# Enable debug mode
APP_DEBUG=true

# View logs
tail -f storage/logs/laravel.log

# Tinker (interactive shell)
php artisan tinker
> User::count()
> Bus::where('status', 'active')->get()
```

### Common Tasks

**Clear Cache:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

**Optimize Application:**
```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
```

**Generate Admin User:**
```php
// In tinker:
php artisan tinker
> User::create([
    'name' => 'Admin',
    'email' => 'admin@transitnexus.local',
    'password' => Hash::make('password'),
    'role' => 'admin'
  ]);
```

---

## Troubleshooting

### 1. "SQLSTATE[HY000]: General error: 1 no such table"

**Cause:** Migrations not run

**Solution:**
```bash
php artisan migrate
# Or if database doesn't exist:
php artisan migrate:refresh --seed
```

### 2. "Trying to access array offset on value of type null"

**Cause:** Missing relationship or null value

**Solution:**
- Check model relationships are defined
- Use optional chaining: `$user?->bookings`
- Add null checks in views

### 3. "Class not found" Errors

**Cause:** Composer autoload not updated

**Solution:**
```bash
composer dump-autoload
```

### 4. Assets Not Loading (CSS/JS)

**Cause:** Frontend assets not compiled

**Solution:**
```bash
npm run build
# Or for development:
npm run dev
```

### 5. Email Not Sending

**Cause:** MAIL_DRIVER or credentials incorrect

**Solution:**
1. Check `.env` MAIL settings
2. For development, use `MAIL_MAILER=log`
3. Check `storage/logs/laravel.log`
4. Verify SMTP credentials for production

### 6. "CSRF Token Mismatch"

**Cause:** Missing CSRF token in form

**Solution:**
- Include `@csrf` in all POST forms:
```blade
<form method="POST" action="/route">
    @csrf
    ...
</form>
```

### 7. Session Not Persisting

**Cause:** SESSION_DRIVER misconfigured or storage not writable

**Solution:**
```bash
# Ensure storage directory is writable
chmod -R 775 storage/
# Clear sessions
php artisan session:clear
```

### 8. Database Connection Refused

**Cause:** Wrong DB credentials or server not running

**Solution:**
- Verify DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD in .env
- For MySQL: ensure MySQL server is running
- For SQLite: check file path is correct and readable

### 9. "Unauthenticated" Error on Protected Routes

**Cause:** User not logged in or session expired

**Solution:**
- Ensure user is authenticated: `Auth::check()`
- Check session lifetime in `config/session.php`
- Clear cookies/cache

### 10. Port 8000 Already in Use

**Cause:** Another process using port

**Solution:**
```bash
# Use different port
php artisan serve --port=8001

# Or kill process on port 8000:
# Windows:
netstat -ano | findstr :8000
taskkill /PID <PID> /F
```

---

## Support & Contributing

For issues, questions, or contributions:
1. Check existing documentation
2. Review code comments and examples
3. Consult Laravel documentation: https://laravel.com/docs
4. Check error logs in `storage/logs/laravel.log`

---

## License

This project is open-source software licensed under the MIT license.

---

**Last Updated:** May 2026
**Version:** 1.0.0
**Maintainer:** TransitNexus Development Team
