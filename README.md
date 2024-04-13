<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About School Management

This is a simple api project to produce some basic facilities to manage a school environment including the following features.

- RBAC infrastructure to manage user roles
- There are four roles: Admin, Principle, Teacher and SchoolStudent
- API tests
- Dockerized using Docker Compose
- Project using MySql as a relational database

## Install and Launch
This project supports Docker, so the only job you should do to launch the project is the following steps:

### Step 1
```shell
docker-compose up -d
```

### Step 2
```shell
docker exec -it php-minikala php artisan migrate --seed
```

Just Finished!
you can use the PostMan collection json file to work with api.

## User Authenticating:
- Admin User 
    - The username and password is `admin`
- Other users:
  - For each access level different users are defined; for instance there are `3 Principles`, `3 Teachers` and `5 Students`
  - The password for all predefined users is: `12345`

## How to manage database
Docker will run a `phpmyadmin` service that you can access it `http://localhost:8081`
The `root password` for mysql is `Pass@123`

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
