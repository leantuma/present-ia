# Present-IA - Employee Attendance Management SaaS

A production-ready multi-tenant SaaS application for managing employee attendance with AI-powered insights, built with Laravel 10+ and Livewire v3.

## Features

### Core Functionality
- **Multi-Tenant Architecture**: Each company operates as an isolated tenant
- **Role-Based Access Control**: Admin, Supervisor, and Employee roles
- **Check-In/Check-Out System**: 
  - Photo capture support
  - Geolocation validation
  - Device information tracking
  - Timestamp recording

### Schedule Management
- Fixed and rotating shift schedules
- Configurable tolerance for late arrivals
- Location-based validation (latitude/longitude + radius)
- Company-wide or employee-specific schedules

### AI-Powered Insights
- Late pattern detection
- Abnormal behavior identification
- Weekly AI-generated summaries
- Automated alert generation

### Dashboard & Reports
- Real-time attendance overview
- Daily statistics and KPIs
- Attendance reports with filtering
- Export capabilities (Excel/PDF - to be implemented)

### Mobile Support
- Progressive Web App (PWA) ready
- Mobile-first responsive design
- Camera integration for photo capture
- Geolocation API support

## Tech Stack

- **Backend**: Laravel 10+
- **Frontend**: Livewire v3
- **Database**: MySQL
- **Authentication**: Custom Laravel authentication
- **Styling**: Tailwind CSS (via CDN)
- **Architecture**: Service layer pattern, Policy-based authorization

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 5.7+ or MariaDB 10.3+
- Node.js and NPM (for asset compilation)

### Step 1: Clone and Install Dependencies

```bash
cd present-ia
composer install
npm install
```

### Step 2: Environment Configuration

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Update your `.env` file with database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=present_ia
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 3: Database Setup

Run migrations:

```bash
php artisan migrate
```

Seed the database with sample data:

```bash
php artisan db:seed
```

This will create:
- A demo company
- Admin user (admin@present-ia.com / password: `password`)
- Supervisor user (supervisor@present-ia.com / password: `password`)
- 5 employee users (employee1@present-ia.com to employee5@present-ia.com / password: `password`)

### Step 4: Storage Link

Create a symbolic link for file storage:

```bash
php artisan storage:link
```

### Step 5: Start Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

For asset compilation (if using Vite):

```bash
npm run dev
```

## Project Structure

```
present-ia/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/          # Authentication controllers
│   │   │   └── DashboardController.php
│   ├── Livewire/              # Livewire components
│   │   ├── CheckInOut.php
│   │   ├── Dashboard.php
│   │   ├── Schedules.php
│   │   └── Reports.php
│   ├── Models/                # Eloquent models
│   │   ├── Company.php
│   │   ├── User.php
│   │   ├── EmployeeProfile.php
│   │   ├── Attendance.php
│   │   ├── Schedule.php
│   │   └── Alert.php
│   ├── Policies/              # Authorization policies
│   │   ├── CompanyPolicy.php
│   │   ├── AttendancePolicy.php
│   │   ├── SchedulePolicy.php
│   │   └── AlertPolicy.php
│   └── Services/              # Business logic services
│       ├── AttendanceService.php
│       ├── AIService.php
│       └── ScheduleService.php
├── database/
│   ├── migrations/            # Database migrations
│   ├── factories/             # Model factories
│   └── seeders/               # Database seeders
└── resources/
    ├── views/
    │   ├── layouts/           # Layout templates
    │   ├── auth/              # Authentication views
    │   └── livewire/          # Livewire component views
```

## Database Models & Relationships

### Company (Tenant)
- Has many Users
- Has many EmployeeProfiles
- Has many Schedules
- Has many Attendances
- Has many Alerts

### User
- Belongs to Company
- Has one EmployeeProfile
- Has many Schedules
- Has many Attendances
- Has many Alerts
- Roles: admin, supervisor, employee

### Attendance
- Belongs to Company
- Belongs to User
- Belongs to Schedule (optional)
- Has many Alerts
- Tracks: check-in/out times, photos, geolocation, device info

### Schedule
- Belongs to Company
- Belongs to User (optional, null = company-wide)
- Has many Attendances
- Supports fixed and rotating shifts

### Alert
- Belongs to Company
- Belongs to User (optional, null = company-wide)
- Belongs to Attendance (optional)
- Types: late_pattern, abnormal_behavior, absence, geolocation_mismatch

## Usage

### For Employees
1. Log in with your credentials
2. Navigate to "Check In/Out"
3. Allow location access when prompted
4. Optionally capture a photo
5. Click "Check In" or "Check Out"
6. View your attendance history in Reports

### For Supervisors
- All employee features
- View schedules
- Create/edit schedules
- View company-wide reports
- View alerts for your team

### For Admins
- All supervisor features
- Full company management
- User management
- Company settings
- Weekly AI summaries

## API Architecture

The application is built with an API-ready architecture. While the current implementation focuses on web interface via Livewire, the service layer can be easily extended to provide REST API endpoints.

### Service Layer Pattern

Business logic is separated into service classes:
- `AttendanceService`: Handles check-in/check-out operations
- `AIService`: Pattern detection and summary generation
- `ScheduleService`: Schedule management operations

### Authorization

Authorization is handled through Laravel Policies:
- `CompanyPolicy`: Company management
- `AttendancePolicy`: Attendance viewing/editing
- `SchedulePolicy`: Schedule management
- `AlertPolicy`: Alert management

## AI Features

The AI layer (currently rule-based) provides:

1. **Late Pattern Detection**: Identifies employees with frequent late arrivals
2. **Abnormal Behavior Detection**: Flags unusual check-in times
3. **Weekly Summaries**: Generates insights and statistics for admins
4. **Automated Alerts**: Creates alerts based on detected patterns

Future enhancements can integrate machine learning models for more advanced pattern recognition.

## PWA Support

The application includes PWA meta tags and is ready for Progressive Web App installation. To fully enable PWA features:

1. Add a web app manifest file
2. Implement service worker for offline support
3. Add app icons

## Development

### Running Tests

```bash
php artisan test
```

### Code Style

The project uses Laravel Pint for code formatting:

```bash
./vendor/bin/pint
```

### Database Migrations

Create a new migration:

```bash
php artisan make:migration create_example_table
```

Run migrations:

```bash
php artisan migrate
```

Rollback last migration:

```bash
php artisan migrate:rollback
```

## Security Considerations

- All passwords are hashed using bcrypt
- CSRF protection enabled
- SQL injection protection via Eloquent ORM
- XSS protection via Blade templating
- Role-based access control
- Multi-tenant data isolation

## Future Enhancements

- [ ] Excel/PDF export implementation
- [ ] Advanced AI/ML integration
- [ ] Real-time notifications
- [ ] Mobile app (React Native/Flutter)
- [ ] Biometric authentication
- [ ] Shift swapping functionality
- [ ] Leave management integration
- [ ] Payroll integration
- [ ] Advanced reporting and analytics

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues, questions, or contributions, please open an issue on the project repository.

## Credits

Built with:
- [Laravel](https://laravel.com)
- [Livewire](https://livewire.laravel.com)
- [Tailwind CSS](https://tailwindcss.com)

---

**Present-IA** - Intelligent Employee Attendance Management
