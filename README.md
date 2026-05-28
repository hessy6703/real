# Employee Performance Evaluation System - PHP Version

A comprehensive, production-ready system for managing employee performance evaluations combining supervisor assessments and anonymous peer reviews with automated scoring and analytics.

## 📋 Project Overview

This system is designed for healthcare organizations and other enterprises requiring robust 360-degree performance evaluation processes. It integrates Google Forms for data collection and Google Sheets for automated calculations, with a PHP backend for advanced analytics and reporting.

## 🏗️ System Architecture

```
real/
├── public/
│   ├── index.php
│   ├── css/
│   │   ├── bootstrap.min.css
│   │   └── style.css
│   ├── js/
│   │   ├── bootstrap.bundle.min.js
│   │   └── app.js
│   └── .htaccess
├── src/
│   ├── Config/
│   │   ├── Database.php
│   │   ├── Settings.php
│   │   └── Constants.php
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── EvaluationController.php
│   │   ├── ReportController.php
│   │   ├── PeerReviewController.php
│   │   └── AdminController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Employee.php
│   │   ├── Evaluation.php
│   │   ├── PeerReview.php
│   │   └── EvaluationScore.php
│   ├── Services/
│   │   ├── CalculationService.php
│   │   ├── NotificationService.php
│   │   ├── SheetsIntegrationService.php
│   │   ├── ReportService.php
│   │   ├── PDFExportService.php
│   │   └── ValidationService.php
│   ├── Middleware/
│   │   ├── Auth.php
│   │   ├── RoleCheck.php
│   │   └── RateLimiter.php
│   ├── Utilities/
│   │   ├── Logger.php
│   │   ├── EmailHelper.php
│   │   ├── DateHelper.php
│   │   └── Validator.php
│   └── Views/
│       ├── layout/
│       │   ├── header.php
│       │   ├── sidebar.php
│       │   └── footer.php
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       ├── dashboard/
│       │   ├── index.php
│       │   ├── my-evaluations.php
│       │   └── peer-reviews.php
│       ├── evaluation/
│       │   ├── form.php
│       │   ├── list.php
│       │   └── detail.php
│       ├── reports/
│       │   ├── individual.php
│       │   ├── department.php
│       │   └── analytics.php
│       └── admin/
│           ├── users.php
│           ├── periods.php
│           └── settings.php
├── database/
│   ├── migrations/
│   │   ├── 001_create_users_table.php
│   │   ├── 002_create_employees_table.php
│   │   ├── 003_create_evaluations_table.php
│   │   ├── 004_create_peer_reviews_table.php
│   │   ├── 005_create_evaluation_scores_table.php
│   │   └── 006_create_audit_log_table.php
│   └── seeds/
│       ├── UsersSeeder.php
│       └── EmployeesSeeder.php
├── tests/
│   ├── Unit/
│   │   ├── CalculationServiceTest.php
│   │   ├── ValidationServiceTest.php
│   │   └── NotificationServiceTest.php
│   └── Feature/
│       ├── EvaluationFeatureTest.php
│       └── AuthFeatureTest.php
├── .env.example
├── .env.docker
├── .htaccess
├── composer.json
├── docker-compose.yml
├── Dockerfile
├── .gitignore
└── README.md
```

## 🎯 Key Features

### Core Functionality
- **Supervisor Evaluations**: Structured scoring across 5 categories
- **Anonymous Peer Reviews**: Multiple peer assessments (minimum 4 required)
- **Automated Calculations**: Real-time scoring and weighting (40% supervisor, 60% peer)
- **Performance Ratings**: Automatic classification
- **Anonymity Controls**: Hidden tracking for HR oversight
- **Email Notifications**: Automated alerts for completion and flagged performers

### Advanced Features
- **Department Analytics**: Performance trends by department
- **Comparison Reporting**: Individual vs. department benchmarks
- **Trend Tracking**: Quarterly performance progression
- **PDF Export**: Professional evaluation reports
- **Dashboard Analytics**: Visual performance insights
- **Compliance Logging**: Full audit trail for fairness verification
- **Role-Based Access**: Admin, HR, Supervisor, Employee, Viewer roles

## 📊 Scoring System

```
Supervisor Total = Performance (0-30) + Patient Care (0-20) + Teamwork (0-20) 
                 + Reliability (0-15) + Initiative & Compliance (0-15)
Maximum = 100 points

Supervisor Weighted = Supervisor Total × 0.40
Peer Average = Average of all peer review totals
Peer Weighted = Peer Average × 0.60

Final Score = Supervisor Weighted + Peer Weighted
Rating = IF(score >= 90, "Outstanding", 
           IF(score >= 80, "Very Good",
           IF(score >= 70, "Good",
           IF(score >= 60, "Fair", "Needs Improvement"))))
```

### Rating Scale
- **90-100**: Outstanding
- **80-89**: Very Good
- **70-79**: Good
- **60-69**: Fair
- **< 60**: Needs Improvement

## 🔧 Setup Instructions

### Prerequisites
- PHP 8.0+
- MySQL 5.7+ or PostgreSQL 12+
- Composer
- Docker (optional)
- Git

### Quick Start

#### 1. Clone Repository
```bash
git clone https://github.com/hessy6703/real.git
cd real
```

#### 2. Configure Environment
```bash
cp .env.example .env
# Edit .env with your settings
```

#### 3. Install Dependencies
```bash
composer install
```

#### 4. Database Setup
```bash
php run-migrations.php
php seed-database.php
```

#### 5. Google Sheets Integration
- Follow `docs/GOOGLE_FORMS_SETUP.md`
- Create Supervisor and Peer Review forms
- Link to Google Sheets
- Add credentials to `.env`

#### 6. Start Development Server
```bash
php -S localhost:8000 -t public
# Access at http://localhost:8000
```

### Docker Setup
```bash
docker-compose up --build
# Access at http://localhost:80
```

## 📋 Configuration Checklist

### Database Configuration
- [ ] MySQL/PostgreSQL database created
- [ ] Database credentials in `.env`
- [ ] Migrations executed
- [ ] Seeds loaded
- [ ] Tables verified

### Google Integration
- [ ] Google Cloud Project created
- [ ] Google Sheets API enabled
- [ ] Service account credentials downloaded
- [ ] Credentials file placed in project
- [ ] Google Forms created and linked to Sheets

### Application Configuration
- [ ] `.env` file configured
- [ ] App URL set
- [ ] Email credentials configured
- [ ] SMTP settings verified
- [ ] Timezone set
- [ ] Logging configured

## 🚀 Deployment

### Local Development
```bash
composer install
php -S localhost:8000 -t public
```

### Docker Deployment
```bash
docker-compose up --build
```

### Production Deployment
See `docs/DEPLOYMENT.md` for:
- Apache/Nginx configuration
- SSL/HTTPS setup
- Database optimization
- Performance tuning
- Monitoring setup

## 📚 Documentation

- **[Implementation Guide](docs/IMPLEMENTATION_GUIDE.md)** - Full setup walkthrough
- **[Google Forms Setup](docs/GOOGLE_FORMS_SETUP.md)** - Form configuration steps
- **[Sheets Formulas](docs/SHEETS_FORMULAS.md)** - All automation formulas
- **[Best Practices](docs/BEST_PRACTICES.md)** - Fairness & compliance guidelines
- **[API Documentation](docs/API.md)** - REST API endpoints
- **[Database Schema](docs/DATABASE.md)** - Table structure and relationships
- **[Deployment Guide](docs/DEPLOYMENT.md)** - Production setup

## 🔐 Security & Compliance

- ✅ Anonymous peer reviews with hidden identity controls
- ✅ Minimum 4 peer reviewers per employee
- ✅ No self-review functionality
- ✅ Complete audit trail logging
- ✅ Role-based access control (RBAC)
- ✅ GDPR-compliant data handling
- ✅ Encrypted credential storage
- ✅ SQL injection prevention
- ✅ XSS protection
- ✅ CSRF token validation
- ✅ Password hashing with bcrypt
- ✅ Session security

## 🔄 Workflow

1. **HR Initiates Evaluation Period**
   - Create evaluation period in system
   - Assign supervisors and peer groups
   - Send notification emails

2. **Supervisors Complete Evaluations**
   - Fill evaluation form in system
   - Submit scores and comments
   - System notifies HR of completion

3. **Peers Provide Reviews**
   - Receive anonymous peer review link
   - Complete review form
   - Anonymity maintained throughout

4. **System Calculates Scores**
   - Automated calculation of weighted scores
   - Performance rating assignment
   - Flagging of low performers

5. **Reports Generated**
   - Individual evaluation reports
   - Department performance summaries
   - Trends and comparisons

6. **Follow-up Actions**
   - HR reviews flagged performers
   - Coaching/improvement plans assigned
   - Quarterly tracking

## 📊 API Endpoints

See [API.md](docs/API.md) for complete endpoint documentation.

### Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `POST /api/auth/register` - User registration

### Evaluations
- `GET /api/evaluations` - List evaluations
- `POST /api/evaluations` - Create evaluation
- `GET /api/evaluations/{id}` - Get evaluation
- `PUT /api/evaluations/{id}` - Update evaluation

### Peer Reviews
- `GET /api/peer-reviews` - List peer reviews
- `POST /api/peer-reviews` - Submit peer review
- `GET /api/peer-reviews/{id}` - Get peer review

### Reports
- `GET /api/reports/individual/{employee_id}` - Individual report
- `GET /api/reports/department/{department}` - Department report
- `GET /api/reports/analytics` - Analytics data
- `POST /api/reports/export/pdf` - Export as PDF

## 🧪 Testing

```bash
# Run all tests
vendor/bin/phpunit

# Run specific test file
vendor/bin/phpunit tests/Unit/CalculationServiceTest.php

# Run with coverage
vendor/bin/phpunit --coverage-html=coverage
```

## 🤝 Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for development guidelines.

## 📄 License

This project is proprietary and confidential.

## 📞 Support

For issues or questions:
1. Check documentation in `/docs` directory
2. Review error logs in `/storage/logs` directory
3. Check GitHub Issues
4. Contact the development team

---

**Last Updated**: May 28, 2026  
**Version**: 1.0.0  
**Status**: Production Ready  
**Language**: PHP 8.0+
