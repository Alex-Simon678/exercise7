## Installation

To run the static analysis tools, you must first install the development dependencies (PHP-CS-Fixer and PHPStan)[cite: 1]. Run the following command in the root of the project:

```bash```
composer install
composer cs-dry-run
composer cs-fix
composer analyse
composer check-all