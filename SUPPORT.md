# Support & Troubleshooting

## Common Issues and Solutions

### 1. Database Connection Error

**Error**: "SQLSTATE[HY000]: General error: 1030 Got error"

**Causes**:
- Database server not running
- Incorrect credentials in .env
- Database user doesn't have permissions
- Connection timeout

**Solutions**:
```bash
# Test database connection
mysql -h localhost -u performance_user -p'password' -e "SELECT 1;"

# Check database user permissions
mysql -u root -p -e "GRANT ALL PRIVILEGES ON performance_db.* TO 'performance_user'@'localhost';"

# Verify .env credentials
grep DB_ /var/www/performance-eval/.env
```

### 2. Email Not Sending

**Issue**: Evaluation notifications not received

**Causes**:
- SMTP credentials incorrect
- Firewall blocking SMTP port
- Email service rate limited

**Solutions**:
```bash
# Test SMTP connection
telnet smtp.mailtrap.io 2525

# Check email configuration
grep MAIL_ /var/www/performance-eval/.env

# Send test email
php -r "require 'vendor/autoload.php'; use App\Services\NotificationService; (new NotificationService())->test();"
```

### 3. Peer Review Not Calculating

**Issue**: Peer average score not updating

**Causes**:
- Minimum 4 peer reviews not submitted
- Peer reviews not marked as SUBMITTED
- Database query error

**Solutions**:
```bash
# Check peer review count
mysql -u performance_user -p'password' performance_db -e \
  "SELECT employee_id, COUNT(*) as count FROM peer_reviews WHERE status='SUBMITTED' GROUP BY employee_id HAVING count < 4;"

# Check peer review status
mysql -u performance_user -p'password' performance_db -e \
  "SELECT id, employee_id, status FROM peer_reviews ORDER BY created_at DESC LIMIT 10;"
```

### 4. Permissions Error

**Error**: "You do not have permission to access this resource"

**Causes**:
- User role not set correctly
- User not authenticated
- Session expired

**Solutions**:
```bash
# Check user role
mysql -u performance_user -p'password' performance_db -e \
  "SELECT id, email, role FROM users WHERE email='user@example.com';"

# Update user role
mysql -u performance_user -p'password' performance_db -e \
  "UPDATE users SET role='HR' WHERE email='user@example.com';"
```

### 5. Evaluation Form Validation Error

**Error**: "Validation failed" on form submission

**Causes**:
- Score outside valid range
- Required field empty
- Invalid data type

**Solutions**:
- Ensure all scores between 0-30, 0-20, 0-20, 0-15, 0-15
- Fill all required fields (marked with *)
- Use numbers only for scores

### 6. PDF Export Fails

**Error**: "PDF export failed"

**Causes**:
- PDF library not installed
- Insufficient permissions
- Memory limit exceeded

**Solutions**:
```bash
# Install PDF library
composer require mpdf/mpdf

# Check file permissions
chown -R www-data:www-data /var/www/performance-eval/storage/uploads

# Increase memory limit in php.ini
memory_limit = 256M
```

---

## Performance Issues

### Slow Evaluation Loading

```bash
# Check slow query log
mysql -u root -p -e "SET GLOBAL slow_query_log=1; SET GLOBAL long_query_time=2;"

# Run query analysis
EXPLAIN SELECT * FROM evaluations WHERE employee_id = 1;

# Add missing index
ALTER TABLE evaluations ADD INDEX idx_employee_id (employee_id);
```

### High Memory Usage

```bash
# Check PHP memory limit
php -i | grep memory_limit

# Update php.ini
memory_limit = 256M

# Restart PHP
sudo systemctl restart php8.0-fpm
```

---

## Getting Help

### Resources
1. Check documentation in `/docs` folder
2. Review error logs:
   - `storage/logs/application.log`
   - `storage/logs/error.log`
   - `storage/logs/database.log`
3. Check GitHub Issues
4. Contact support team

### Providing Debug Information

When reporting issues, include:
```
- PHP version: php -v
- Database: mysql --version
- Error logs: tail -f storage/logs/application.log
- .env configuration (without credentials)
- Steps to reproduce
- Expected vs actual behavior
```

---

**Last Updated**: May 28, 2026
