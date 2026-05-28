# Employee Performance Evaluation System

A comprehensive, production-ready system for managing employee performance evaluations combining supervisor assessments and anonymous peer reviews with automated scoring and analytics.

## 📋 Project Overview

This system is designed for healthcare organizations and other enterprises requiring robust 360-degree performance evaluation processes. It integrates Google Forms for data collection and Google Sheets for automated calculations and reporting.

## 🏗️ System Architecture

```
real/
├── docs/
│   ├── IMPLEMENTATION_GUIDE.md
│   ├── GOOGLE_FORMS_SETUP.md
│   ├── SHEETS_FORMULAS.md
│   └── BEST_PRACTICES.md
├── scripts/
│   ├── google-apps-script/
│   │   ├── supervisor-form.gs
│   │   ├── peer-review-form.gs
│   │   ├── automation.gs
│   │   └── email-alerts.gs
│   ├── python/
│   │   ├── sheets_sync.py
│   │   ├── analytics.py
│   │   └── reporting.py
│   └── sql/
│       ├── schema.sql
│       └── migrations/
├── backend/
│   ├── models/
│   │   ├── employee.py
│   │   ├── supervisor_review.py
│   │   ├── peer_review.py
│   │   └── evaluation_score.py
│   ├── controllers/
│   │   ├── evaluation_controller.py
│   │   ├── reporting_controller.py
│   │   └── user_controller.py
│   ├── services/
│   │   ├── calculation_service.py
│   │   ├── notification_service.py
│   │   ├── sheets_integration_service.py
│   │   └── pdf_export_service.py
│   └── app.py
├── frontend/
│   ├── dashboard/
│   │   ├── index.html
│   │   ├── css/
│   │   └── js/
│   ├── reports/
│   │   ├── individual_report.html
│   │   └── department_report.html
│   └── admin/
│       ├── form_management.html
│       └── user_management.html
├── config/
│   ├── settings.py
│   ├── constants.py
│   └── credentials.template.json
├── tests/
│   ├── test_calculations.py
│   ├── test_sheets_integration.py
│   └── test_reporting.py
├── requirements.txt
├── .env.example
└── docker-compose.yml
```

## 🎯 Key Features

### Core Functionality
- **Supervisor Evaluations**: Structured scoring across 5 categories
- **Anonymous Peer Reviews**: Multiple peer assessments (minimum 4 required)
- **Automated Calculations**: Real-time scoring and weighting (40% supervisor, 60% peer)
- **Performance Ratings**: Automatic classification (Outstanding, Very Good, Good, Fair, Needs Improvement)
- **Anonymity Controls**: Hidden tracking for HR oversight
- **Email Notifications**: Automated alerts for completion and flagged performers

### Advanced Features
- **Department Analytics**: Performance trends by department
- **Comparison Reporting**: Individual vs. department benchmarks
- **Trend Tracking**: Quarterly performance progression
- **PDF Export**: Professional evaluation reports
- **Dashboard Analytics**: Visual performance insights
- **Compliance Logging**: Full audit trail for fairness verification

## 📊 Scoring System

### Calculation Formula
```
Supervisor Total = Performance (0-30) + Patient Care (0-20) + Teamwork (0-20) 
                 + Reliability (0-15) + Initiative & Compliance (0-15)
Maximum = 100 points

Supervisor Weighted = Supervisor Total × 0.40
Peer Average = Average of all peer review totals
Peer Weighted = Peer Average × 0.60

Final Score = Supervisor Weighted + Peer Weighted
Performance Rating = IF(score >= 90, "Outstanding", 
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
- Google Account with Forms & Sheets access
- Python 3.8+
- Docker (optional)
- Git

### Quick Start

1. **Clone Repository**
   ```bash
   git clone https://github.com/hessy6703/real.git
   cd real
   ```

2. **Configure Environment**
   ```bash
   cp .env.example .env
   # Edit .env with your Google Sheets API credentials
   ```

3. **Set Up Google Forms**
   - Follow `docs/GOOGLE_FORMS_SETUP.md`
   - Create Supervisor Evaluation Form
   - Create Peer Review Form
   - Link both to Google Sheets

4. **Deploy Automation Scripts**
   - Copy Google Apps Script code from `scripts/google-apps-script/`
   - Add to Google Sheets triggers

5. **Install Python Dependencies**
   ```bash
   pip install -r requirements.txt
   ```

6. **Initialize Database**
   ```bash
   python backend/app.py --init-db
   ```

7. **Start Backend Server**
   ```bash
   python backend/app.py
   # Server runs on http://localhost:5000
   ```

## 📋 Configuration Checklist

### Google Forms Configuration

**Form 1: Supervisor Evaluation**
- [ ] Employee ID field (Short answer)
- [ ] Employee Full Name field (Short answer)
- [ ] Department field (Dropdown)
- [ ] Job Title field (Short answer)
- [ ] Evaluation Period field (Short answer)
- [ ] 5 scoring categories with validation (0-100)
- [ ] Supervisor overall assessment sections
- [ ] Supervisor name & signature
- [ ] Link to "Supervisor Responses" Sheet

**Form 2: Peer Review (Anonymous)**
- [ ] Email collection DISABLED
- [ ] Sign-in NOT required
- [ ] Multiple responses ENABLED
- [ ] Employee ID field (Short answer)
- [ ] Employee Name field (Dropdown)
- [ ] Department field (Dropdown)
- [ ] 5 scoring categories with validation (0-100)
- [ ] Optional peer comments section
- [ ] Link to "Peer Responses" Sheet

### Google Sheets Setup

**Supervisor Responses Sheet**
- [ ] Columns: Employee ID, Name, Department, Job Title, Period
- [ ] Scoring columns: Performance, Patient Care, Teamwork, Reliability, Initiative
- [ ] Calculation columns: Total, Weighted (40%)
- [ ] Comments columns

**Peer Responses Sheet**
- [ ] Columns: Timestamp, Employee ID, Name, Department
- [ ] Scoring columns: Performance, Patient Care, Teamwork, Reliability, Initiative
- [ ] Comments columns
- [ ] Calculation: Average score per employee

**Summary Sheet**
- [ ] Employee list with totals
- [ ] Supervisor Weighted (40%)
- [ ] Peer Weighted (60%)
- [ ] Final Score
- [ ] Rating Classification
- [ ] Department Summary

## 🚀 Deployment

### Local Development
```bash
docker-compose up --build
```

### Production Deployment
- See `docs/IMPLEMENTATION_GUIDE.md` for cloud deployment options
- Recommended: Google Cloud Run + Cloud SQL

## 📚 Documentation

- **[Implementation Guide](docs/IMPLEMENTATION_GUIDE.md)** - Full setup walkthrough
- **[Google Forms Setup](docs/GOOGLE_FORMS_SETUP.md)** - Form configuration steps
- **[Sheets Formulas](docs/SHEETS_FORMULAS.md)** - All automation formulas
- **[Best Practices](docs/BEST_PRACTICES.md)** - Fairness & compliance guidelines

## 🔐 Security & Compliance

- ✅ Anonymous peer reviews with hidden identity controls
- ✅ Minimum 4 peer reviewers per employee
- ✅ No self-review functionality
- ✅ Complete audit trail logging
- ✅ Role-based access control (HR, Supervisor, Employee)
- ✅ GDPR-compliant data handling
- ✅ Encrypted credential storage

## 🔄 Workflow

1. **HR Initiates Evaluation Period**
   - Create evaluation period in system
   - Assign supervisors and peer groups
   - Send notification emails

2. **Supervisors Complete Evaluations**
   - Fill Supervisor Evaluation Form
   - Submit scores and comments
   - System notifies HR of completion

3. **Peers Provide Reviews**
   - Receive anonymous peer review link
   - Complete Peer Review Form (4+ required)
   - Anonymity maintained throughout

4. **System Calculates Scores**
   - Automated calculation of weighted scores
   - Performance rating assignment
   - Flagging of low performers

5. **Reports Generated**
   - Individual evaluation reports
   - Department performance summaries
   - Trends and comparisons
   - Flagged performers notification to HR

6. **Follow-up Actions**
   - HR reviews flagged performers
   - Coaching/improvement plans assigned
   - Quarterly tracking

## 📊 Advanced Features (Future Roadmap)

- [ ] Looker Studio Dashboard Integration
- [ ] Department performance ranking
- [ ] Individual trend tracking
- [ ] Monthly/quarterly automated reports
- [ ] Machine learning for performance prediction
- [ ] Integration with HR management systems
- [ ] Mobile app for evaluators
- [ ] Real-time notifications and reminders

## 🤝 Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for development guidelines.

## 📄 License

This project is proprietary and confidential.

## 📞 Support

For issues or questions, contact the HR Technology team.

---

**Last Updated**: May 28, 2026  
**Version**: 1.0.0  
**Status**: Production Ready
