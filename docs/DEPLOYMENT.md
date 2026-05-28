# Deployment Guide - Employee Performance Evaluation System (PHP)

## Table of Contents
1. [Pre-Deployment Checklist](#pre-deployment-checklist)
2. [Production Server Setup](#production-server-setup)
3. [Security Configuration](#security-configuration)
4. [Database Setup](#database-setup)
5. [Application Deployment](#application-deployment)
6. [Monitoring & Maintenance](#monitoring--maintenance)
7. [Troubleshooting](#troubleshooting)

---

## Pre-Deployment Checklist

### Code & Dependencies
- [ ] All tests passing (`vendor/bin/phpunit`)
- [ ] Code linting passed (`vendor/bin/phpstan`)
- [ ] No hardcoded credentials in code
- [ ] All environment variables documented
- [ ] Dependencies up to date
- [ ] Composer.lock file committed

### Security
- [ ] SSL certificate obtained
- [ ] Firewall rules configured
- [ ] Database backup strategy in place
- [ ] Email credentials secured
- [ ] Google API credentials protected
- [ ] File permissions verified

### Infrastructure
- [ ] Production server ready
- [ ] Database server ready
- [ ] Email service configured
- [ ] Load balancer configured (if applicable)
- [ ] CDN setup (optional)
- [ ] Monitoring tools installed

---

## Production Server Setup

### System Requirements

```bash
# OS: Ubuntu 20.04 LTS or similar
# PHP: 8.0+ with extensions: pdo, pdo_mysql, curl, mbstring, openssl
# MySQL: 5.7+ or PostgreSQL 12+
# Web Server: Apache 2.4+ or Nginx 1.18+
# Memory: Minimum 2GB RAM
# Storage: Minimum 50GB free space
# Bandwidth: Minimum 10Mbps
```

### Apache Configuration

```apache
# /etc/apache2/sites-available/performance-eval.conf

<VirtualHost *:443>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/performance-eval/public
    
    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/yourdomain.com.crt
    SSLCertificateKeyFile /etc/ssl/private/yourdomain.com.key
    SSLCertificateChainFile /etc/ssl/certs/yourdomain.com.ca-bundle
    
    # Security Headers
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    
    <Directory /var/www/performance-eval>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
        
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteBase /
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^(.*)$ index.php [L,QSA]
        </IfModule>
    </Directory>
    
    # Disable direct access to sensitive directories
    <Directory /var/www/performance-eval/src>
        Deny from all
    </Directory>
    
    <Directory /var/www/performance-eval/config>
        Deny from all
    </Directory>
    
    <Directory /var/www/performance-eval/storage>
        Deny from all
    </Directory>
    
    # PHP Configuration
    <FilesMatch "\.php$">
        SetHandler "proxy:unix:/run/php-fpm.sock|fcgi://localhost"
    </FilesMatch>
    
    ErrorLog ${APACHE_LOG_DIR}/performance-eval-error.log
    CustomLog ${APACHE_LOG_DIR}/performance-eval-access.log combined
</VirtualHost>

# HTTP to HTTPS redirect
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    RewriteEngine On
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>
```

### Nginx Configuration

```nginx
# /etc/nginx/sites-available/performance-eval

upstream php_upstream {
    server unix:/run/php-fpm.sock;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    
    ssl_certificate /etc/ssl/certs/yourdomain.com.crt;
    ssl_certificate_key /etc/ssl/private/yourdomain.com.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    
    # Security Headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    
    root /var/www/performance-eval/public;
    index index.php;
    
    access_log /var/log/nginx/performance-eval-access.log combined;
    error_log /var/log/nginx/performance-eval-error.log warn;
    
    location ~ \.php$ {
        fastcgi_pass php_upstream;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # Deny access to sensitive directories
    location ~ ^/(src|config|storage|database)/ {
        deny all;
    }
    
    # URL Rewriting
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
}

# HTTP to HTTPS redirect
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}
```

---

## Security Configuration

### File Permissions

```bash
# Set correct permissions
chown -R www-data:www-data /var/www/performance-eval
chmod -R 755 /var/www/performance-eval
chmod -R 750 /var/www/performance-eval/storage
chmod -R 750 /var/www/performance-eval/config
chmod 640 /var/www/performance-eval/.env
```

### Environment Configuration

```bash
# Create production .env file
cp .env.example /var/www/performance-eval/.env

# Edit .env with production values
APP_ENV=production
APP_DEBUG=false
SECRET_KEY=generate-strong-random-key
DATABASE_URL=mysql://user:password@localhost:3306/performance_db
MAIL_FROM=noreply@yourdomain.com
```

### Firewall Rules

```bash
# UFW (Uncomplicated Firewall)
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS
sudo ufw enable
```

---

## Database Setup

### Create Database and User

```sql
CREATE DATABASE performance_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'performance_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON performance_db.* TO 'performance_user'@'localhost';
FLUSH PRIVILEGES;
```

### Run Migrations

```bash
cd /var/www/performance-eval
php run-migrations.php migrate
```

### Backup Strategy

```bash
# Daily backup script
#!/bin/bash
BACKUP_DIR="/backups/performance_db"
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u performance_user -p'password' performance_db > $BACKUP_DIR/backup_$DATE.sql
gzip $BACKUP_DIR/backup_$DATE.sql
# Delete backups older than 30 days
find $BACKUP_DIR -name "*.sql.gz" -mtime +30 -delete
```

---

## Application Deployment

### Clone Repository

```bash
cd /var/www
git clone https://github.com/hessy6703/real.git performance-eval
cd performance-eval
```

### Install Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### Create Required Directories

```bash
mkdir -p storage/logs storage/uploads
chown -R www-data:www-data storage
chmod -R 755 storage
```

### Set Permissions

```bash
chown -R www-data:www-data /var/www/performance-eval
find /var/www/performance-eval -type f -exec chmod 644 {} \;
find /var/www/performance-eval -type d -exec chmod 755 {} \;
chmod 750 storage
chmod 640 .env
```

### Enable Service

```bash
# Create systemd service file
sudo nano /etc/systemd/system/performance-eval.service

[Unit]
Description=Performance Evaluation System
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/performance-eval
ExecStart=/usr/bin/php -S 0.0.0.0:8000 -t public
Restart=always

[Install]
WantedBy=multi-user.target

# Enable and start
sudo systemctl enable performance-eval.service
sudo systemctl start performance-eval.service
```

---

## Monitoring & Maintenance

### Application Monitoring

```bash
# Monitor PHP FPM
sudo systemctl status php*-fpm

# Monitor logs
tail -f /var/www/performance-eval/storage/logs/application.log

# Check disk usage
df -h /var/www/performance-eval

# Check database
mysql -u performance_user -p'password' -e "SHOW PROCESSLIST;" performance_db
```

### Performance Optimization

```bash
# Enable PHP OPcache
php -i | grep "Zend OPcache"

# Enable gzip compression in Apache
a2enmod deflate
a2enmod headers

# Enable page caching headers
# (Add to .htaccess or web server config)
```

### Log Rotation

```bash
# Create /etc/logrotate.d/performance-eval
/var/www/performance-eval/storage/logs/*.log {
    daily
    rotate 30
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    postrotate
        systemctl reload apache2 > /dev/null 2>&1 || true
    endscript
}
```

### Automated Backups

```bash
# Add to crontab
0 2 * * * /var/www/performance-eval/backup.sh
```

---

## Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error
```bash
# Check error logs
tail -f /var/log/apache2/performance-eval-error.log
tail -f /var/www/performance-eval/storage/logs/application.log

# Check file permissions
ls -la /var/www/performance-eval/public/index.php
```

#### 2. Database Connection Failed
```bash
# Test connection
mysql -h localhost -u performance_user -p'password' -e "SELECT 1;"

# Check credentials in .env
grep DATABASE /var/www/performance-eval/.env
```

#### 3. Email Not Sending
```bash
# Check SMTP configuration
echo "Test email" | sendmail -v test@example.com

# Check mail logs
tail -f /var/log/mail.log
```

---

## Backup & Recovery

### Backup Procedure

```bash
#!/bin/bash
# Full backup script
BACKUP_DIR="/backups/performance_eval"
DATE=$(date +%Y%m%d_%H%M%S)
TARGET="$BACKUP_DIR/backup_$DATE"

mkdir -p $TARGET

# Backup database
mysqldump -u performance_user -p'password' performance_db > $TARGET/database.sql

# Backup application files
tar -czf $TARGET/application.tar.gz /var/www/performance-eval/

# Backup .env
cp /var/www/performance-eval/.env $TARGET/

echo "Backup completed: $TARGET"
```

### Recovery Procedure

```bash
# Restore from backup
TARGET="/backups/performance_eval/backup_2026-05-28_02-00-00"

# Restore database
mysql -u performance_user -p'password' performance_db < $TARGET/database.sql

# Restore files
tar -xzf $TARGET/application.tar.gz -C /

# Restore .env
cp $TARGET/.env /var/www/performance-eval/

# Fix permissions
chown -R www-data:www-data /var/www/performance-eval

echo "Recovery completed"
```

---

**Last Updated**: May 28, 2026  
**Version**: 1.0.0
