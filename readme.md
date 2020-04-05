Simple Laravel + Vue Project (SPA)
- User management system

Installation and development:
- Clone using the github link: https://github.com/edsel77/laravel-vue-project.git
- Database name: php_exam_database, port: 3306
- run:
    - composer install
    - php artisan key:generate
    - php artisan migrate
    - php artisan db:seed
    - php artisan serve
    - npm install
    - npm run watch

User login:
- Pick one user from the users table
- Login requirements are username and password
- Password is initially the same with the username upon registration
