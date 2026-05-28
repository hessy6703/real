<?php
/**
 * CONTRIBUTING.md - Contributing Guidelines
 */
?>
# Contributing to Employee Performance Evaluation System

## Code of Conduct

This project is committed to providing a welcoming and inspiring community for developers. All contributors are expected to uphold the highest standards of professional and ethical conduct.

## How to Contribute

### Reporting Bugs

1. **Check existing issues** - Ensure the bug hasn't already been reported
2. **Provide details**:
   - PHP version
   - Database system and version
   - Steps to reproduce
   - Expected vs actual behavior
   - Error messages and logs
   - Screenshots (if applicable)

3. **Example issue**:
   ```
   Title: Peer review scores not calculating correctly
   
   Description:
   When submitting a peer review with specific scores, the total is incorrect.
   
   Steps to reproduce:
   1. Submit peer review with scores: perf=28, care=19, team=18
   2. Check summary page
   3. Total shows 64 instead of 65
   
   Error:
   [Error message from logs]
   ```

### Suggesting Enhancements

1. **Use clear title** - Describe the feature in one sentence
2. **Provide motivation** - Explain why this enhancement would be useful
3. **Suggest implementation** - If possible, describe how it could be implemented
4. **List examples** - Show how other systems implement similar features

### Submitting Pull Requests

1. **Fork the repository**
   ```bash
   git clone https://github.com/your-username/real.git
   cd real
   ```

2. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Make your changes**
   - Follow existing code style
   - Add tests for new functionality
   - Update documentation as needed

4. **Commit your changes**
   ```bash
   git commit -m "feat: Add peer review anonymity validation"
   ```
   Use conventional commit format:
   - `feat:` New feature
   - `fix:` Bug fix
   - `docs:` Documentation
   - `test:` Tests
   - `refactor:` Code refactoring

5. **Push to your fork**
   ```bash
   git push origin feature/your-feature-name
   ```

6. **Create Pull Request**
   - Provide clear description
   - Reference related issues
   - Include screenshots if UI changes
   - List any breaking changes

## Code Style Guidelines

### PHP
- PSR-12 standard
- 4 spaces for indentation
- Docblocks for all classes and methods
- Type hints required

```php
<?php
/**
 * Calculate final score
 *
 * @param array $supervisorScores Supervisor scores array
 * @param array $peerScores Array of peer review scores
 * @return array Final calculations
 * @throws InvalidArgumentException
 */
public function calculateFinalScore(array $supervisorScores, array $peerScores): array
{
    // Implementation
}
```

### Database
- Table names: lowercase with underscores
- Column names: lowercase with underscores
- Use FOREIGN KEY constraints
- Include indexes for performance

### SQL
```sql
CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    supervisor_id INT NOT NULL,
    evaluation_period VARCHAR(20) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (supervisor_id) REFERENCES employees(id),
    INDEX idx_employee_id (employee_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Testing

All pull requests must include tests.

```bash
# Run tests
vendor/bin/phpunit

# Run specific test
vendor/bin/phpunit tests/Unit/CalculationServiceTest.php

# Generate coverage report
vendor/bin/phpunit --coverage-html=coverage
```

## Documentation

- Update README.md for user-facing changes
- Update API.md for API changes
- Add docblocks to all new functions
- Include examples in comments

## Performance Checklist

- [ ] No N+1 queries
- [ ] Appropriate indexes on tables
- [ ] Efficient loops and conditionals
- [ ] Minimal database queries
- [ ] Cache used where appropriate

## Security Checklist

- [ ] Input validation on all data
- [ ] SQL injection prevention (prepared statements)
- [ ] XSS prevention (output escaping)
- [ ] CSRF protection
- [ ] Password hashing with bcrypt
- [ ] Role-based access control
- [ ] No hardcoded secrets
- [ ] Error messages don't leak information

## Review Process

1. **Automated checks**
   - Tests pass
   - Code style adhered to
   - No security issues

2. **Code review**
   - At least 1 maintainer approval
   - Feedback addressed
   - Discussion resolved

3. **Merge**
   - Squash commits if needed
   - Merge to main branch
   - Close related issues

## Release Process

1. Version numbering: Semantic Versioning (Major.Minor.Patch)
2. Update CHANGELOG.md
3. Tag release: `v1.0.0`
4. Create GitHub release
5. Announce in documentation

## Questions?

- Check existing documentation
- Review similar features/PRs
- Ask in GitHub Discussions
- Contact maintainers

---

Thank you for contributing!
